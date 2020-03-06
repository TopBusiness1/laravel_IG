<?php

namespace Modules\PolizzaCar\Service;

// use App\FtpLog;
// use App\User;

use Modules\PolizzaCar\Entities\PolizzaCar;
use Modules\PolizzaCar\Entities\PolizzaCarStatus;
use Modules\PolizzaCar\Entities\PolizzaCarProcurement;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CsvExportService
{

    public function exportCsv()
    {
        $Facility_Code = config('vaance.facility_code'); // @TODO Change

        $columnTitles = [
            'Facility_Code',
            'PolicyHolder_Name',
            'PolicyHolder_Address',
            'PolicyHolder_City',
            'PolicyHolder_PostalCode',
            'PolicyHolder_PR',
            'PolicyHolder_VAT',
            'CVG_Type',
            'CVG_DecennialLiability',
            'CITTA\' DI RESIDENZA ASSICURATO',
            'NAZIONE DI RESIDENZA ASSICURATO',
            'DATA INIZIO VALIDITA\' COPERTURA',
            'DATA FINE VALIDITA\' COPERTURA',
            'TIPO MOVIMENTO',
        ];

        $output = "";
        foreach ($columnTitles as $columnTitle) {
            $output .= $columnTitle . ";";
        }
        $output = substr($output, 0, -1) . "\r\n";

        $fileDate = now();
        $dateFrom = now();
        if (now()->format('H:i') >= config('vaance.export_time')) { // tomorrow's file
            $fileDate->addDay();
        } else {
            $dateFrom->subDay();
        }
        $filename = $Facility_Code . '_' . $fileDate->format('Ymd');
        $timeFrom = $dateFrom->toDateString() . ' ' . config('vaance.export_time');

        /* $polizze = PolizzaCar::withTrashed()
            ->with('import_model')
            ->has('import_model')
            ->where(function ($q) use ($timeFrom) {
                return $q->where('created_at', '>=', $timeFrom)
                    ->orWhere('updated_at', '>=', $timeFrom)
                    ->orWhere('deleted_at', '>=', $timeFrom);
            })
            ->whereHas('status_id', function ($q) {
                return $q->where('id', 5); // Simple polizze
            })
            ->get(); */
        
        $polizze = PolizzaCar::withTrashed()
            ->where(function ($q) use ($timeFrom) {
                return $q->where('created_at', '>=', $timeFrom)
                    ->orWhere('updated_at', '>=', $timeFrom)
                    ->orWhere('deleted_at', '>=', $timeFrom);
            })
            ->whereHas('status', function ($q) {
                return $q->where('id', 5); // polizze waiting to be sent
            })
            ->get();

        foreach ($polizze as $polizza) {
            $status = "A";
            if ($polizza->updated_at != $polizza->created_at) {
                $status = "M";
            }
            if ($polizza->insurance_start_date_changed_at) {
                $status = "R";
            }
            if ($polizza->deleted_at || $polizza->insurance_stop_request) {
                $status = "E";
            }
            if ($polizza->insurance_expire_date) {
                if (Carbon::createFromFormat('d/m/Y', $polizza->insurance_expire_date)->format('Y-m-d') >= '2020-10-01') {
                    $status = 'R';
                }
            }

            $output .= $Facility_Code . ";";
            $output .= $polizza->company_name . ";";
            $output .= $polizza->company_address . ";";

            $output .= $polizza->company_vat . ";";
            //$output .= $polizza->import_model->id . ";5;";
            //$output .= $polizza->entry_creation_date . ";" . $polizza->surname . ";";
            //$output .= $polizza->city . ";" . $polizza->country . ";" . $polizza->insurance_start_date . ";";
            $output .= $polizza->date_request . ";" . $status . "\r\n";
        }

        Storage::disk('exports')->put($filename . '.csv', $output);

        return true;
    }

    public function uploadToFTP()
    {
        $filename = config('vaance.facility_code') . '_' . date('Ymd') . '.csv';
        $filePath = storage_path('app/exports/' . $filename);

        $rowsAmount = 0;

        try {
            if (!file_exists($filePath)) {
                FtpLog::create([
                    'filename'      => $filename,
                    'success'       => 0,
                    'error_message' => 'Nothing to upload: file ' . $filename . ' not found on the server',
                ]);

                return false;
            }

            $reader     = new \SpreadsheetReader($filePath);
            foreach ($reader as $row) {
                $rowsAmount++;
            }

            // Not counting header row
            if ($rowsAmount > 0) {
                $rowsAmount--;
            }

            Storage::disk('ftp')->put($filename, fopen($filePath, 'r+'));
        } catch (\Exception $e) {
            FtpLog::create([
                'filename'      => $filename,
                'rows_amount'   => $rowsAmount,
                'success'       => 0,
                'error_message' => $e->getMessage(),
            ]);

            return false;
        }

        // Success!
        FtpLog::create([
            'filename'      => $filename,
            'rows_amount'   => $rowsAmount,
            'success'       => 1,
        ]);

        return true;
    }

}
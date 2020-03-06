<?php

namespace Modules\PolizzaCar\Http\Controllers;

use Modules\Platform\Core\Http\Controllers\ModuleCrudController;
use Modules\PolizzaCar\Datatables\PolizzaCarDatatable;
use Modules\PolizzaCar\Entities\PolizzaCar;
use Modules\PolizzaCar\Http\Forms\PolizzaCarForm;
use Modules\PolizzaCar\Http\Requests\PolizzaCarRequest;
use Illuminate\Http\Request;
use PDFMerger;
use setasign\Fpdi\Fpdi;
use Modules\Platform\User\Entities\User;
use Modules\Core\Notifications\GenericNotification;
use Modules\Platform\Notifications\Entities\NotificationPlaceholder;
use Modules\Platform\Core\Datatable\ActivityLogDataTable;
use Illuminate\Support\Str;
use App\Notifications\UserInvitationNotification;
use Modules\PolizzaCar\Entities\PolizzaCarProcurement;
use Modules\PolizzaCar\Entities\PianoTariffario;

use Modules\PolizzaCar\Service\CsvExportService;

use Session;
class PolizzaCarController extends ModuleCrudController
{

    protected $datatable = PolizzaCarDatatable::class;
    protected $formClass = PolizzaCarForm::class;
    protected $storeRequest = PolizzaCarRequest::class;
    protected $updateRequest = PolizzaCarRequest::class;
    protected $entityClass = PolizzaCar::class;

    protected $moduleName = 'polizzacar';

    protected $showMassActionButtons = true;

    protected $permissions = [
        'browse' => 'polizzacar.browse',
        'create' => 'polizzacar.create',
        'update' => 'polizzacar.update',
        'destroy' => 'polizzacar.destroy'
    ];

    protected $moduleSettingsLinks = [

        ['route' => 'polizzacar.category.index', 'label' => 'settings.category'],
        ['route' => 'polizzacar.procurement.index', 'label' => 'settings.procurement'],
        ['route' => 'polizzacar.status.index', 'label' => 'settings.status'],
        ['route' => 'polizzacar.works_type.index', 'label' => 'settings.works_type'],
        ['route' => 'polizzacar.piano_tariffario.index', 'label' => 'settings.piano_tariffario']


    ];

    protected $settingsPermission = 'polizzacar.settings';

    protected function setupCustomButtons()
    {
        $user = auth()->user();

        if ($this->entity->pdf_signed_contract == '') { // se non c'e contratto firmato
            $this->customShowButtons[] = array(
                'href' => route('polizzacar.polizzacar.docusign', $this->entity->id),
                'attr' => [
                'class' => 'btn btn-crud bg-pink waves-effect pull-right docuAjax',
                ],
                'label' => 'DocuSign Ajax' // trans('PolizzaCar::PolizzaCar.approve')
            );
            $this->customShowButtons[] = array(
                'href' => route('polizzacar.polizzacar.docusign', $this->entity->id),
                'attr' => [
                'class' => 'btn btn-crud bg-pink waves-effect pull-right',
                ],
                'label' => 'DocuSign' // trans('PolizzaCar::PolizzaCar.approve')
            );
        } else {
            
        }

        // if ( Auth::user()->id == 1 ) { // admin
        if (in_array($user->role_id, [1, 2])) { // Admin & Supervisor
            if ($this->entity->status_id == 2) { // waiting approval
                $this->customShowButtons[] = array(
                    'href' => route('polizzacar.polizzacar.approve', $this->entity->id),
                    'attr' => [
                    'class' => 'btn btn-crud bg-orange waves-effect pull-right',                       
                    ],
                    'label' => trans('PolizzaCar::PolizzaCar.approve')
                );
            }
            if ($this->entity->status_id == 4) { // waiting verify
                $this->customShowButtons[] = array(
                    'href' => route('polizzacar.polizzacar.sendOrder', $this->entity->id),
                    'attr' => [
                    'class' => 'btn btn-crud bg-orange waves-effect pull-right',
                    ],
                    'label' => trans('PolizzaCar::PolizzaCar.send_order')
                );
            }
            if ($this->entity->status_id == 5) { // elaborazione
                $this->customShowButtons[] = array(
                    'href' => route('polizzacar.polizzacar.downloadCsv'),
                    'attr' => [
                    'class' => 'btn btn-crud bg-blue waves-effect pull-right',
                    ],
                    'label' => trans('PolizzaCar::PolizzaCar.download_csv')
                );
            }
        }
        if ($this->entity->status_id == 3) {
                $this->customShowButtons[] = array(
                    'href' => route('polizzacar.polizzacar.print', $this->entity->id),
                    'attr' => [
                    'class' => 'btn btn-crud bg-blue waves-effect pull-right',
                    'target' => '_blank',
                    ],
                    'label' => trans('PolizzaCar::PolizzaCar.quotePDF')
                );
            $this->customShowButtons[] = array(
                'href' => '#',
                'attr' => [
                'class' => 'btn btn-crud bg-green waves-effect pull-right btnUploadFile',
                ],
                'label' => trans('PolizzaCar::PolizzaCar.upload_signed_doc')
            );
        }
        if ($this->entity->status_id == 6) {
            $this->customShowButtons[] = array(
                'href' => route('polizzacar.polizzacar.printCertificato', $this->entity->id),
                'attr' => [
                'class' => 'btn btn-crud bg-green waves-effect pull-right',
                'target' => '_blank',
                ],
                'label' => trans('PolizzaCar::PolizzaCar.CertificatoPDF')
            );
        }
        
    }

     protected $sectionButtons = [

        [
            'section' => 'contractor',
            'class' => 'm-r-10',
            'id' => 'contractor-copy-from-procurement',
            'href' => '#',
            'label' => 'copy_from_procurement',
            'icon' => 'fa fa-copy',
            'title' => 'copy_from_procurement',
        ]
    ]; 

    protected $cssFiles = [
        'VAANCE_PolizzaCar.css'
    ];

    protected $jsFiles = [
        'VAANCE_PolizzaCar.js'
    ];
    
    protected $showFields = [

        'information' => [
            'id' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'date_request' => [
                'type' => 'date',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6'
                ],
            'procurement_id' => [
                'type' => 'manyToOne',
                'relation' => 'procurement',
                'column' => 'company_name',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
                ],
            'status_id' => [
                'type' => 'manyToOne',
                'relation' => 'status',
                'column' => 'name',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6 status'
                ],
        ],

        'contractor' => [
            'company_name' => [
                'type' => 'text',
                'col-class' => 'col-lg-3 col-md-3 col-sm-4 col-xs-6'
            ],
            'company_vat' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'company_email' => [
                'type' => 'text',
                'col-class' => 'col-lg-3 col-md-3 col-sm-4 col-xs-6'
            ],

            'company_phone' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'company_address' => [
                'type' => 'text',
                'col-class' => 'col-lg-3 col-md-3 col-sm-4 col-xs-6 clear-left'
            ],

            'company_city' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],

            'company_cap' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],

            'company_provincia' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'country_id' => [
                'type' => 'manyToOne',
                'relation' => 'country',
                'column' => 'name',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
        ],
        'procurement' => [
            'works_type_details' => [
                'type' => 'manyToOne',
                'relation' => 'works_type',
                'column' => 'name',
                'col-class' => 'col-lg-7 col-md-7 col-sm-6 col-xs-6'
                ],
            'works_place' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6'
            ],
            'primary_works_place' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6'
            ],
            'works_descr' => [
                'type' => 'text',
                'col-class' => 'col-lg-6 col-md-6 col-sm-4 col-xs-6'
            ],
            'works_duration_mm' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6 clear-left'
            ],    
            'risk_id' => [
                'type' => 'manyToOne',
                'relation' => 'tariffa',
                'column' => 'name',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6'
            ],
            'coeff_tariffa' => [
                'type' => 'decimal',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6'
            ],
            'tax_rate' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-6 col-xs-6'
            ], 
        ],
        'warranties' => [
            'sezione_a' => [
                'type' => 'text',
                'col-class' => 'col-lg-12 col-md-12'
            ],
            'partita_1' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-md-4 col-sm-12 col-xs-12'
            ],
            'car_p1_limit_amount' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'car_p1_premium_gross' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'car_p1_premium_net' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            
            'partita_2' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-md-4 col-sm-12 col-xs-12'
            ],
            'car_p2_limit_amount' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'car_p2_premium_gross' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'car_p2_premium_net' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'partita_3' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-md-4 col-sm-12 col-xs-12'
            ],
            'car_p3_limit_amount' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'car_p3_premium_gross' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'car_p3_premium_net' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'sezione_b' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-md-4'
            ],
            'car_civil_liability' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'sezione_b_terms' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'total_labels' => [
                'type' => 'text',
                'col-class' => 'col-lg-4 col-md-4 col-sm-12 col-xs-12 clear-left'
            ],
            'partite_total' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'total_gross' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            'total_net' => [
                'type' => 'text',
                'col-class' => 'col-lg-2 col-md-2 col-sm-4 col-xs-6'
            ],
            
        ],

    ];

    protected $languageFile = 'PolizzaCar::PolizzaCar';

    protected $routes = [
        'index' => 'polizzacar.polizzacar.index',
        'create' => 'polizzacar.polizzacar.create',
        'show' => 'polizzacar.polizzacar.show',
        'edit' => 'polizzacar.polizzacar.edit',
        'store' => 'polizzacar.polizzacar.store',
        'destroy' => 'polizzacar.polizzacar.destroy',
        'update' => 'polizzacar.polizzacar.update',
        'import' => 'polizzacar.polizzacar.import',
        'import_process' => 'polizzacar.polizzacar.import.process'
    ];

    public function __construct()
    {
        parent::__construct();
    }

        /**
     * Overwritten show function
     *
     * @param $identifier
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($identifier)
    {
        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();


        $entity = $repository->find($identifier);


        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        if ($this->blockEntityOwnableAccess()) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $this->entityIdentifier = $entity->id;

        $this->entity = $entity;

        $this->beforeShow(request(), $entity);

        $view = view('polizzacar::show');

        $view->with('entity', $entity);
        $view->with('show_fields', $this->showFields);
        $view->with('show_fileds_count', count($this->showFields));

        $view->with('next_record', $repository->next($entity));
        $view->with('prev_record', $repository->prev($entity));
        $view->with('disableNextPrev', $this->disableNextPrev);

        $this->setupCustomButtons();
        $this->setupActionButtons();
        $view->with('customShowButtons', $this->customShowButtons);
        $view->with('actionButtons',$this->actionButtons);
        $view->with('commentableExtension', false);
        $view->with('actityLogDatatable', null);
        $view->with('attachmentsExtension', true);
        $view->with('entityIdentifier', $this->entityIdentifier);


        $view->with('hasExtensions', false);

        $view->with('relationTabs', $this->setupRelationTabs($entity));

        $view->with('baseIcons', $this->baseIcons);

        /*
         * Extensions
         */

        if (in_array(self::COMMENTS_EXTENSION, class_uses($this->entity))) {
            $view->with('commentableExtension', true);
            $view->with('hasExtensions', true);
        }
        if (in_array(self::ACTIVITY_LOG_EXTENSION, class_uses($this->entity))) {
            $activityLogDataTable = \App::make(ActivityLogDataTable::class);
            $activityLogDataTable->setEntityData(get_class($entity), $entity->id);
            $view->with('actityLogDatatable', $activityLogDataTable->html());
            $view->with('hasExtensions', true);
        }
        if (in_array(self::ATTACHMENT_EXTENSION, class_uses($this->entity))) {
            $view->with('attachmentsExtension', true);
            $view->with('hasExtensions', true);
        }

        /*
         * add pdf parts.
         */
        if (!empty($entity->pdf_signed_contract) || !empty($entity->pdf_payment_proof)) {
            $view->with('hasPdfs', true);
            $pdfshowFields = [
                'signed_pdf' => [
                    'pdf_signed_contract' => [
                        'type' => 'aTag',
                        'col-class' => 'col-lg-5 col-md-5 col-sm-5 col-xs-5',
                        'href' => route('polizzacar.polizzacar.showPDF', ['pdf'=>$entity->pdf_signed_contract]),
                    ],
                    'pdf_payment_proof' => [
                        'type' => 'aTag',
                        'col-class' => 'col-lg-5 col-md-5 col-sm-5 col-xs-5',
                        'href' => route('polizzacar.polizzacar.showPDF', ['pdf'=>$entity->pdf_payment_proof]),
                    ],
                ],
            ];
            $view->with('pdfshowFields', $pdfshowFields);
        } else {
            $view->with('hasPdfs', false);
        }

        \JavaScript::put([
            'polizza_Id' => $entity->id,
        ]);

        return $view;
    }

    /**
     * Polizza CAR Approve and Invite.
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function approvePolizzaCar($identifier)
    {
        $user = auth()->user();

        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        // when the record is approved by Admin or Supervisor

        $polizza = PolizzaCar::find($entity->id);

        activity()
            ->causedBy($user)
            ->performedOn($polizza)
            ->log('approved');

        //check if the user already exists
        $existingUsersEmails = User::pluck('email')->toArray();

        if (!in_array($entity->company_email, $existingUsersEmails)) 
        {
            $user = User::create([
                'name'                   => $entity->company_email,
                'email'                  => $entity->company_email,
                'password'               => bcrypt(Str::random(8)),
                'verification_token'     => Str::random(64)
            ]);
            $user->roles()->attach(5);
            // user created with role 5

            $user->notify(new UserInvitationNotification($polizza, 'invite'));
            //user notified with email where he will set password

            flash(trans('PolizzaCar::PolizzaCar.invite_sent_with_login'))->success();
            // notification on screen

        } else {

            // the user exists so it will be notified
            $setWho = $polizza->company_email;

            \Notification::route('mail', $setWho)
                ->notify(new UserInvitationNotification($polizza, 'send_to_contractor')); 

                $user = User::where('email', $polizza->company_email)->first();

                $messaggio = 'Preventivo n. '. $entity->id .' - '. $entity->company_name .' approvato. In attesa di Documenti Firmati.';
                
                $placeholder = new NotificationPlaceholder();
                $placeholder->setRecipient($user);
                $placeholder->setContent($messaggio);

                $placeholder->setColor('bg-green');
                $placeholder->setIcon('assignment');
                $placeholder->setUrl(route('polizzacar.polizzacar.show', $polizza));
                
                $user->notify(new GenericNotification($placeholder));
    
                $Supervisor = User::where('role_id', 2)->first();

                $messaggio = 'Preventivo n. '. $entity->id .' - '. $entity->company_name .' approvato. In attesa di Documenti firmati.';
    
                $placeholder = new NotificationPlaceholder();
                $placeholder->setContent($messaggio);
                $placeholder->setRecipient($Supervisor);

                $placeholder->setColor('bg-green');
                $placeholder->setIcon('assignment');
                $placeholder->setUrl(route('polizzacar.polizzacar.show', $polizza));
    
                $Supervisor->notify(new GenericNotification($placeholder));
                
                flash(trans('PolizzaCar::PolizzaCar.invite_sent'))->success();
        }

        $polizza->update([
            'status_id'      => 3, // Waiting Signed Documents
        ]);

        // $procurement = PolizzaCarProcurement::where('id', $entity->procurement_id)->first();
        // $procurement->update([
        //     'has_policy'      => 1, // mark as used so it can't be reused
        // ]);

        // $procurement = PolizzaCarProcurement::where('id', $entity->procurement_id)->first();
        $procurement = PolizzaCarProcurement::where('id', $entity->procurement_id)->first();
        if($procurement) {
            $procurement->update([
                'insurance_policy' => $polizza->id, // mark as used so it can't be reused
            ]);
        }
        return redirect()->route('polizzacar.polizzacar.show',$polizza->id);
        // return redirect(route($this->routes['index']));

    }

    /**
     * Polizza CAR print
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function printPolizzaCar($identifier)
    {

        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        /* if ($this->blockEntityOwnableAccess()) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        } */

        $this->entityIdentifier = $entity->id;

        $this->entity = $entity;

        $printData = [
            'entity' => $this->entity
        ];

        /* $pdf = \PDF::loadView('polizzacar::pdf.print', $printData); */

        $fullPathToFile = storage_path('pdf/7_Preventivo_CAR_CHUBB_ITALGAS_0719.pdf');

        $pdf = new FPDI();
        $pdf->AddPage();
        $pageCount = $pdf->setSourceFile($fullPathToFile);
        
        // import a page
        $templateId = $pdf->importPage(1);
        // get the size of the imported page
        $size = $pdf->getTemplateSize($templateId);

        // use the imported page
        $pdf->useTemplate($templateId);
        $pdf->SetFont('Times', 'B', '10'); 
        // from entity
        $pdf->SetXY(70, 66);
        $pdf->Write(0, $entity->company_name);
        $pdf->SetXY(70, 72);
        $pdf->Write(0, $entity->company_vat);
        $pdf->SetXY(70, 94);
        $pdf->Write(0, $entity->works_type->name);
        $pdf->SetXY(70, 100);
        $pdf->Write(0, $entity->works_place);
        $pdf->SetXY(70, 106);
        $pdf->Write(0, $entity->primary_works_place);
        $pdf->SetXY(100, 130);
        $pdf->Write(0, EURO . ' ' . number_format($entity->car_p1_limit_amount,2));
        $pdf->SetXY(100, 136);
        $pdf->Write(0, EURO . ' ' . number_format($entity->car_p2_limit_amount,2));
        $pdf->SetXY(100, 142);
        $pdf->Write(0, EURO . ' ' . number_format($entity->car_p3_limit_amount,2));
        $pdf->SetXY(125, 130);
        $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p1_premium_net));
        $pdf->SetXY(125, 136);
        $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p2_premium_net));
        $pdf->SetXY(125, 142);
        $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p3_premium_net));
        $pdf->SetXY(145, 130);
        $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p1_premium_gross));
        $pdf->SetXY(145, 136);
        $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p2_premium_gross));
        $pdf->SetXY(145, 142);
        $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p3_premium_gross));
        $pdf->SetXY(50, 182);
        $pdf->Write(1, date('d/m/Y', strtotime($entity->date_request)));
        $pdf->SetXY(40, 189);
        $pdf->Write(1, $entity->works_duration_mm / 12 . ' anni');
        //$pdf->SetXY(50, 136);
        //$pdf->Write(1, date('d/m/Y', strtotime($entity->date_effect)));
        // Output the new PDF
        // $pdf->Output();       
        
        $output = $identifier . '_7_Preventivo_CAR_CHUBB_ITALGAS_0719.pdf';

        $pdf->Output($output, 'F');

        $pdf_full = new PDFMerger();

        // Add all the pages of the PDF to merge 
        $pdf_full->addPDF(storage_path('pdf/1_Cover_CAR_CHUBB_Italgas_0919.pdf'), 'all');
        $pdf_full->addPDF(storage_path('pdf/2_Allegati_3&4_CAR_Italgas_0919.pdf'), 'all');
        $pdf_full->addPDF(storage_path('pdf/4_Consensi_Contraente_CAR_Italgas_0919.pdf'), 'all');
        $pdf_full->addPDF(storage_path('pdf/3_Privacy_InformativaStrategica_0918.pdf'), 'all');
        $pdf_full->addPDF(storage_path('pdf/5_Cover_CAR_CHUBB_ITALGAS_0919_SI.pdf'), 'all');
        $pdf_full->addPDF(base_path('public/' . $identifier . '_7_Preventivo_CAR_CHUBB_ITALGAS_0719.pdf'), 'all');
        $pdf_full->addPDF(storage_path('pdf/6_SetInformativo_Convenzione_CAR_italgas_170419.pdf'), 'all'); 

        // $pdf_full->merge('download', $identifier.'_CAR.pdf'); 
        $pdf_full->merge('stream', $identifier.'_CAR.pdf'); 

        if (file_exists(base_path('public/'.$identifier . '_7_Preventivo_CAR_CHUBB_ITALGAS_0719.pdf'))) {
            unlink(base_path('public/'.$identifier . '_7_Preventivo_CAR_CHUBB_ITALGAS_0719.pdf'));
        }

        //return $pdf->inline($identifier . '_CAR.pdf');
    }

    /**
     * Polizza CAR print
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function printCertificatoPolizzaCar($identifier)
    {

        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        /* if ($this->blockEntityOwnableAccess()) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        } */

        $this->entityIdentifier = $entity->id;

        $this->entity = $entity;

        $printData = [
            'entity' => $this->entity
        ];

        /* $pdf = \PDF::loadView('polizzacar::pdf.print', $printData); */

        $fullPathToFile = storage_path('pdf/8_Certificato_CAR_CHUBB_ITALGAS_0719.pdf');
        
        define('EURO',chr(128));
        
        $pdf = new FPDI();
        $pdf->AddPage();
        $pageCount = $pdf->setSourceFile($fullPathToFile);
        
            // import a page
            $templateId = $pdf->importPage(1);
            // get the size of the imported page
            $size = $pdf->getTemplateSize($templateId);

            // use the imported page
            $pdf->useTemplate($templateId);
            $pdf->SetFont('Times', 'B', '10'); 
            // from entity
            $pdf->SetXY(70, 66);
            $pdf->Write(0, $entity->company_name);
            $pdf->SetXY(70, 72);
            $pdf->Write(0, $entity->company_vat);
            $pdf->SetXY(70, 94);
            $pdf->Write(0, $entity->works_type->name);
            $pdf->SetXY(70, 100);
            $pdf->Write(0, $entity->works_place);
            $pdf->SetXY(70, 106);
            $pdf->Write(0, $entity->primary_works_place);
            $pdf->SetXY(100, 130);
            $pdf->Write(0, EURO . ' ' . number_format($entity->car_p1_limit_amount,2));
            $pdf->SetXY(100, 136);
            $pdf->Write(0, EURO . ' ' . number_format($entity->car_p2_limit_amount,2));
            $pdf->SetXY(100, 142);
            $pdf->Write(0, EURO . ' ' . number_format($entity->car_p3_limit_amount,2));
            $pdf->SetXY(125, 130);
            $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p1_premium_net));
            $pdf->SetXY(125, 136);
            $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p2_premium_net));
            $pdf->SetXY(125, 142);
            $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p3_premium_net));
            $pdf->SetXY(145, 130);
            $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p1_premium_gross));
            $pdf->SetXY(145, 136);
            $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p2_premium_gross));
            $pdf->SetXY(145, 142);
            $pdf->Write(0, iconv('UTF-8', 'windows-1252', $entity->car_p3_premium_gross));
            $pdf->SetXY(50, 182);
            $pdf->Write(1, date('d/m/Y', strtotime($entity->date_request)));
            $pdf->SetXY(40, 189);
            $pdf->Write(1, $entity->works_duration_mm / 12 . ' anni');
            // $pdf->SetXY(50, 136);
            // $pdf->Write(1, date('d/m/Y', strtotime($entity->date_effect)));
            // Output the new PDF
            // $pdf->Output();       
            
            $output = $identifier . '_8_Certificato_CAR_CHUBB_ITALGAS_0719.pdf';

            $pdf->Output($output, 'F');

        if (file_exists(base_path('public/'.$identifier . '_8_Certificato_CAR_CHUBB_ITALGAS_0719.pdf'))) {
            unlink(base_path('public/'.$identifier . '_8_Certificato_CAR_CHUBB_ITALGAS_0719.pdf'));
        }

        return $pdf->Output($identifier . '_8_Certificato_CAR_CHUBB_ITALGAS_0719.pdf', 'I'); 
    }

    /**
     * Polizza CAR send to contractor.
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function sendToContractorPolizzaCar($identifier)
    {
        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        $polizza = PolizzaCar::find($entity->id);

        // check whether current user is Supervisor.
        $user = auth()->user();

        if ($user->role_id == 2) {
            $user->notify(new UserInvitationNotification($polizza, 'send_to_contractor'));  
            // now set the status_id of the polizzacar as 3.
            $polizza->status_id = 3;
            $polizza->save();

            flash(trans('core::core.success_send_to_contractor'))->success();
            // return redirect(route($this->routes['index']));
            return redirect()->route('polizzacar.polizzacar.show',$polizza->id);

        } else {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }
    }

    /**
     * Polizza CAR Sign and Accept.
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function signAndAcceptPolizzaCar($identifier)
    {
        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        $polizza = PolizzaCar::find($entity->id);

        // check whether current user is Supervisor.
        $user = auth()->user();

        if ($user->role_id == 2) {
            $user->notify(new UserInvitationNotification($polizza, 'send_to_contractor'));  
            // now set the status_id of the polizzacar as 3.
            $polizza->status_id = 3;
            $polizza->save();

            flash(trans('core::core.success_send_to_contractor'))->success();
            // return redirect(route($this->routes['index']));
            return redirect()->route('polizzacar.polizzacar.show',$polizza->id);

        } else {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }
    }


    
    /**
     * Polizza upload 2 pdf files.
     *
     * @param $identifier
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function uploadPDFfilesPolizzaCar(Request $request)
    {
        // $request->validate([
        //     'pdf_signed_contract' => 'required|file',
        //     'pdf_payment_proof' => 'required|file',
        // ]);

        // save files.
        $polizza = PolizzaCar::find($request->polizzaId);

        $fullPath = storage_path('signed_pdf/'. $polizza->id .'/');
                    
        if (!\File::exists($fullPath))
        {
            \File::makeDirectory($fullPath, 0755, true, true);
        }
        // $polizza->update([
        //     'pdf_signed_contract' => $fileName // set filename to DB
        // ]);
                    
        $field = $request->field_name;

        // $fileName = $request->$field->getClientOriginalName();
        // $request->$field->move($fullPath, $fileName);

        $fileName = $request->$field->getClientOriginalName();
        $request->$field->move(storage_path('uploaded_docs'), $fileName);


        // update table.
        $polizza->$field = $fileName;

        $polizza->save();

        return response()->json(['pdf_signed_contract' => $polizza->pdf_signed_contract, 'pdf_payment_proof' => $polizza->pdf_payment_proof]);

        
        // return response()->json(['uploaded' => '/upload/'.$fileName]);
    }

    public function sendOrderPolizzaCar($identifier)
    {
        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        $polizza = PolizzaCar::find($entity->id);
        // $polizza = PolizzaCar::find($identifier);
        $polizza->status_id = 5;
        $polizza->save();
        (new CsvExportService())->exportCsv();
        $polizza->order_sent_at = \Carbon\Carbon::now();
        

            $Supervisor = User::where('role_id', 2)->first();

            $messaggio = 'Polizza n. '. $polizza->id .' - '. $polizza->company_name .' inviata alla compagnia.';

            $placeholder = new NotificationPlaceholder();
            $placeholder->setContent($messaggio);
            $placeholder->setRecipient($Supervisor);
                $placeholder->setColor('bg-blue');
                $placeholder->setIcon('compare_arrows');
                $placeholder->setUrl(route('polizzacar.polizzacar.show', $polizza));
            $Supervisor->notify(new GenericNotification($placeholder));
           

            $user = User::where('email', $polizza->company_email)->first();

            $placeholder = new NotificationPlaceholder();
            $placeholder->setContent($messaggio);
            $placeholder->setRecipient($user);
                $placeholder->setColor('bg-blue');
                $placeholder->setIcon('compare_arrows');
                $placeholder->setUrl(route('polizzacar.polizzacar.show', $polizza));

            $user->notify(new GenericNotification($placeholder));

        flash(trans('PolizzaCar::PolizzaCar.order_request'))->success();
            // return redirect(route($this->routes['index']));
            return redirect()->route('polizzacar.polizzacar.show',$polizza->id);

    }

    public function receiveResultPolizzaCar($identifier)
    {
        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        $polizza = PolizzaCar::find($entity->id);

        // here to do something about reading the response 

        $polizza->status_id = 6;
        $polizza->save();

        $setWho = $polizza->company_email;

        \Notification::route('mail', $setWho)
            ->notify(new UserInvitationNotification($polizza, 'order_complete')); 

            $Supervisor = User::where('role_id', 2)->first();

            $messaggio = 'Polizza n. '. $polizza->id .' - '. $polizza->company_name .' attivata con successo.';

            $placeholder = new NotificationPlaceholder();
            $placeholder->setContent($messaggio);
            $placeholder->setRecipient($Supervisor);
                $placeholder->setColor('bg-green');
                $placeholder->setIcon('check_circle_outline');
                $placeholder->setUrl(route('polizzacar.polizzacar.show', $polizza));
            $Supervisor->notify(new GenericNotification($placeholder));
           

            $user = User::where('email', $polizza->company_email)->first();

            $placeholder = new NotificationPlaceholder();
            $placeholder->setContent($messaggio);
            $placeholder->setRecipient($user);
                $placeholder->setColor('bg-green');
                $placeholder->setIcon('check_circle_outline');
                $placeholder->setUrl(route('polizzacar.polizzacar.show', $polizza));

            $user->notify(new GenericNotification($placeholder));

        flash(trans('PolizzaCar::PolizzaCar.order_sent'))->success();
            // return redirect(route($this->routes['index']));
            return redirect()->route('polizzacar.polizzacar.show',$polizza->id);

    }

    protected function setupActionButtons()
    {
        $this->actionButtons[] = array(
            'href' => route($this->routes['create'], ['copy' => $this->entity->id]),
            'attr' => [

            ],
            'label' => trans('core::core.btn.copy')
        );
    }

    /**
     * Return company settings to set in quote
     * @return \Illuminate\Http\JsonResponse
     */
    public function companySettings()
    {
        return response()->json([
            'data' => SettingsHelper::companySettings()
        ]);
    }
    
    /**
     * return data from related procurement
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function copyDataFromProcurement(Request $request)
    {
        $procurementId = $request->get('procurement_id', null);

        $procurement = PolizzaCarProcurement::find($procurementId);

        $procurementData = [
            'company_name' => '',
            'company_vat' => '',
            'company_email' => '',
            'company_phone' => '',
            'company_address' => '',
            'company_city' => '',
            'company_cap' => '',
            'company_provincia' => '',
            'country' => '',
            'works_type_details' => '',
            'works_descr' => '',
            'works_place' => '',
            'primary_works_place' => '',
            'works_duration_mm' => '',
            
        ];

        if (!empty($procurement)) {
            $procurementData = [
                'company_name' => $procurement->company_name,
                'company_vat' => $procurement->company_vat,
                'company_email' => $procurement->company_email,
                'company_phone' => $procurement->company_phone,
                'company_address' => $procurement->company_address,
                'company_city' => $procurement->company_city,
                'company_cap' => $procurement->company_cap,
                'company_provincia' => $procurement->company_provincia,
                'country' => $procurement->country,
                'works_type_details' => $procurement->works_type_details,
                'works_descr' => $procurement->works_descr,
                'works_place' => $procurement->works_place,
                'primary_works_place' => $procurement->primary_works_place,
                'works_duration_mm' => $procurement->works_duration_mm,
                
            ];
        }

        return response()->json(
            [
                'data' => $procurementData
            ]
        );
    }
    
    public function downloadCsv(Request $request)
    {
        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        (new CsvExportService())->exportCsv();

        // abort_if(Gate::denies('import_model_import'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        /* $filename = config('vaance.facility_code') . '_' . date('Ymd') . '.csv';

        $file = storage_path('app/exports/' . $filename);
        $file = 'ITALGAS_20191201.csv';
        var_dump($file);
        
        if (!file_exists($file)) {
            return back()->withMessage(trans('cruds.user.csv_file_not_found'));
        }

        return response()->download($file);*/
    }

    /**
     * return data from related tariffa
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getTariffa(Request $request)
    {
        $riskId = $request->get('risk_id', null);

        $tariffa = PianoTariffario::find($riskId);

        $tariffaData = [
            'mm_24' => '',
            'mm_36' => '',
            'tax_rate' => '',
            'commission' => '',
        
        ];

        if (!empty($tariffa)) {
            $tariffaData = [
                'mm_24' => $tariffa->mm_24,
                'mm_36' => $tariffa->mm_36,
                'tax_rate' => $tariffa->tax_rate,
                'commission' => $tariffa->commission,
                
            ];
        }

        return response()->json(
            [
                'data' => $tariffaData
            ]
        );
    }

    public function updatePdfStatusPolizzaCar(Request $request)
    {
        $return = ['status' => 'error', 'msg' => ''];

        $polizza = PolizzaCar::find($request->polizzaId);

        // check whether 2 pdf files uploaded.
        if (empty($polizza->pdf_signed_contract)) {            
            $return['msg'] = 'Ooh, Signed contract pdf is empty. Please upload it.';
        } else if (empty($polizza->pdf_payment_proof)) {
            $return['msg'] = 'Ooh, Payment proof pdf is empty. Please upload it.';            
        } else {
            // change status.
            $polizza->status_id = 4;
            $polizza->save();

            // send email to Supervisor
            $Supervisor = User::where('role_id', 2)->first();

            if ($Supervisor) {
                // $Supervisor->notify(new UserInvitationNotification($polizza, 'check_pdfs'));    
                $invitation_notification = new UserInvitationNotification($polizza, 'check_pdfs');
                $Supervisor = User::where('role_id', 2)->first();

                $Supervisor->notify($invitation_notification);    
            } else {
                // abort(403, 'Ooh, Can not find Supervisor. Please check it out.');
                flash(trans('core::core.can_not_find_supervisor'))->error();
            }
            flash(trans('PolizzaCar::PolizzaCar.uploaded_doc_success'))->success();

            $Supervisor = User::where('role_id', 2)->first();

            $messaggio = 'Polizza n. '. $polizza->id .' - '. $polizza->company_name .' documenti caricati da verificare.';

            $placeholder = new NotificationPlaceholder();
            $placeholder->setContent($messaggio);
            $placeholder->setRecipient($Supervisor);
            $placeholder->setColor('bg-orange');
                    $placeholder->setIcon('playlist_add_check');
                    $placeholder->setUrl(route('polizzacar.polizzacar.show', $polizza));

            $Supervisor->notify(new GenericNotification($placeholder));

            $user = User::where('email', $polizza->company_email)->first();
    
                $messaggio = 'Polizza n. '. $polizza->id .' - '. $polizza->company_name .' documenti caricati con successo.';
    
                $placeholder = new NotificationPlaceholder();
                $placeholder->setContent($messaggio);
                $placeholder->setRecipient($user);
                    $placeholder->setColor('bg-orange');
                    $placeholder->setIcon('playlist_add_check');
                    $placeholder->setUrl(route('polizzacar.polizzacar.show', $polizza));

                $user->notify(new GenericNotification($placeholder));


            $return['status'] = 'ok';
            
        }
        // flash(trans('PolizzaCar::PolizzaCar.uploaded_doc_success'))->success();
        return response()->json(
            [
                'data' => $return
            ]
        );

        // return redirect()->route('polizzacar.polizzacar.show',$polizza->id);

        
    }

    public function showPdfFile($pdfFile)
    {

        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        // $fullPathToFile = storage_path('pdf/7_Preventivo_CAR_CHUBB_ITALGAS_0719.pdf');
        
        // $fullPathToFile = storage_path('signed_pdf/' . $pdfFile );

        $fullPathToFile = storage_path('uploaded_docs/' . $pdfFile );


        if (file_exists($fullPathToFile)) {
            $pdf_full = new PDFMerger();
            $pdf_full->addPDF($fullPathToFile, 'all');
            $pdf_full->merge('stream', $pdfFile); 
        } else {
            abort('404');
        }

        
    }

    /**
     * Function invoked after entity store
     * @param $request
     * @param $entity
     */
    public function afterStore($request, &$entity)
    {
        // $user = auth()->user();

        $entity->status_id = 2;
        $entity->save();

        $polizza = PolizzaCar::find($entity->id);

        activity()
            ->performedOn($polizza)
            ->log('created');

        $Supervisor = User::where('role_id', 2)->first();

        $messaggio = 'Preventivo n. '. $entity->id .' - '. $entity->company_name .' creato. In attesa di approvazione.';

        $placeholder = new NotificationPlaceholder();
        $placeholder->setContent($messaggio);
        $placeholder->setColor('bg-teal');
        $placeholder->setIcon('how_to_reg');
        $placeholder->setUrl(route('polizzacar.polizzacar.show', $polizza));

        $placeholder->setRecipient($Supervisor);

        $Supervisor->notify(new GenericNotification($placeholder));

        $polizza = PolizzaCar::find($entity->id);

        $procurement = PolizzaCarProcurement::where('id', $entity->procurement_id)->first();
        if ($procurement) {
            $procurement->update([
                'insurance_policy'      => $polizza->id, // mark as used so it can't be reused
            ]);
        }

        // current polizzacar stauts_id is 2
        // now send approving email to user role 2
        // $Supervisor = User::where('role_id', 2)->first();

        if ($Supervisor) {
            $Supervisor->notify(new UserInvitationNotification($polizza, 'approve'));    
        } else {
            // abort(403, 'Ooh, Can not find Supervisor. Please check it out.');
            flash(trans('core::core.can_not_find_supervisor'))->error();
        }

    }

    public function afterUpdate($request, &$entity)
    {
        

    }

    public function signatureDocusign($identifier)
    {
        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        $polizza = PolizzaCar::where('id', $entity->id)->first();
        $envelope_id = $polizza->envelope_id;
        
        $client = new \DocuSign\Rest\Client([
			'username'       => env('DOCUSIGN_USERNAME'),
			'password'       => env('DOCUSIGN_PASSWORD'),
			'integrator_key' => env('DOCUSIGN_INTEGRATOR_KEY')
        ]);

        if ($envelope_id == '') {

        $fullPathToFile = storage_path('pdf/8_Certificato_CAR_CHUBB_ITALGAS_0719.pdf');
        $b64Doc = chunk_split(base64_encode(file_get_contents($fullPathToFile)));
        
        $envelopeSummary = $client->envelopes->createEnvelope(
           array (
            'status' => 'sent',
            'emailSubject' => 'Polizza n. '. $entity->id .' - '. $entity->company_name .' in attesa di firma',
            'emailBlurb' => 'Please sign the Polizza CAR to start the application process.',
            'documents' => [
              array (
                'documentId' => '1',
                'name' => 'Document',
                'fileExtension' => 'pdf',
                'documentBase64' => $b64Doc,
              )
            ],
            'recipients' => array (
              'signers' => [ array (
                  'tabs' => array (
                    'signHereTabs' => [
                      array (
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'xPosition' => 380,
                        'yPosition' => 650
                      )
                    ],
                    'textTabs' => [
                      array (
                        'name' => 'Ragione Sociale',
                        'value' => $entity->company_name,
                        'locked' => 'true',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'Ragione Sociale',
                        'anchorXOffset' => '100',
                        'anchorYOffset' => '0'
                      ),
                      array (
                        'name' => 'CF / Partita IVA',
                        'value' => $entity->company_vat,
                        'locked' => 'true',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'Partita IVA',
                        'anchorXOffset' => '80',
                        'anchorYOffset' => '0'
                      ),
                      array (
                        'name' => 'Certificato',
                        'value' => $entity->id,
                        'locked' => 'true',
                        'tabLabel' => 'Numero Certificato',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'CERTIFICATO',
                        'anchorXOffset' => '20',
                        'anchorYOffset' => '15'
                      ),
                      array (
                        'name' => 'Tipologia lavori',
                        'value' => $entity->works_type->name,
                        'locked' => 'true',
                        'tabLabel' => 'Tipologia lavori',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'Tipologia lavori',
                        'anchorXOffset' => '100',
                        'anchorYOffset' => '0'
                      ),
                      array (
                        'name' => 'Provincia di esecuzione',
                        'value' => $entity->works_place,
                        'locked' => 'true',
                        'tabLabel' => 'Provincia di esecuzione',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'Provincia di esecuzione',
                        'anchorXOffset' => '120',
                        'anchorYOffset' => '0'
                      ),
                      array (
                        'name' => 'Luogo di esecuzione',
                        'value' => $entity->primary_works_place,
                        'locked' => 'true',
                        'tabLabel' => 'Luogo di esecuzione',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'Luogo di esecuzione',
                        'anchorXOffset' => '120',
                        'anchorYOffset' => '0'
                      ),
                      array (
                        'name' => 'P1',
                        'value' => $entity->car_p1_limit_amount,
                        'locked' => 'true',
                        'tabLabel' => 'Partita 1',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'Partita 1 - OPERE',
                        'anchorXOffset' => '150',
                        'anchorYOffset' => '0'
                      ),
                      array (
                        'name' => 'Durata',
                        'value' => $entity->works_duration_dd,
                        'locked' => 'true',
                        'tabLabel' => 'Durata',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'DURATA',
                        'anchorXOffset' => '120',
                        'anchorYOffset' => '0'
                      ),/* 
                      array (
                        'name' => 'P2',
                        'value' => $entity->car_p2_limit_amount,
                        'locked' => 'true',
                        'tabLabel' => 'Partita 2',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'Partita 2 – OPERE PREESISTENTI',
                        'anchorXOffset' => '120',
                        'anchorYOffset' => ''
                      ) */
                    ],
                    /* 'numberTabs' => [
                       array (
                        'name' => 'P1',
                        'value' => $entity->car_p1_limit_amount,
                        'locked' => 'true',
                        'tabLabel' => 'Partita 1',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'OPERE',
                        'anchorXOffset' => '100',
                        'anchorYOffset' => ''
                      ), 
                      
                      array (
                        'name' => 'P3',
                        'value' => $entity->car_p3_limit_amount,
                        'locked' => 'true',
                        'tabLabel' => 'Partita 3',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'Partita 3 - OPERE',
                        'anchorXOffset' => '100',
                        'anchorYOffset' => ''
                      )
                    ], */
                    'emailTabs' => [
                      array (
                        'name' => 'Email',
                        'value' => 'adrianovacca@gmail.com',
                        'tabLabel' => 'Email',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'E-mail:',
                        'anchorXOffset' => '100',
                        'anchorYOffset' => '-2'
                      )
                    ],
                    'formulaTabs' => [
                      array (
                        'formula' => '[Amount]/[PaymentDuration]',
                        'name' => 'MonthlyPayment',
                        'tabLabel' => 'MonthlyPayment',
                        'documentId' => '1',
                        'recipientId' => '1',
                        'pageNumber' => '1',
                        'anchorString' => 'Monthly Payment:',
                        'anchorXOffset' => '200',
                        'anchorYOffset' => '-2'
                      )
                    ]
                  ),
                  'name' => $entity->company_name,
                  'email' => $entity->company_email,
                  'recipientId' => '1',
                  // 'accessCode' => '12345',  // if access code selected
                  'clientUserId' => '1001'  // if embedded signing selected
                )
              ]
            )
          )

        );

        $envelope_id = $envelopeSummary['envelope_id'];
        
        $polizza = PolizzaCar::where('id', $entity->id)->first();
        // dd($polizza);
        $polizza->update([
            'envelope_id' => $envelope_id // mark as used so it can't be reused
        ]);

    }
        
        $returnUrl = 'http://localhost:8000/polizzacar/polizzacar/docusign/feedback/'. $polizza->id;
        
        $result = $client->envelopes->createRecipientView($envelope_id, array(
            'userName' => $polizza->company_name,
            'email' => $polizza->company_email,
            'AuthenticationMethod' => 'none',
            'clientUserId' => '1001',  // if embedded signing selected
            'returnUrl' => $returnUrl
         ));

         // dd($request->ajax());

        return ['envelope_id' => $envelope_id, 'url' => $result['url']];

        // return \Redirect::to($result['url']);

        // if(!empty($polizza)){
        //    flash('OK' /* trans('core::core.record_converted')*/ )->success();

        //    return redirect()->route('polizzacar.polizzacar.show',$polizza->id);
        // }

    }

    public function getDocusignFeefback(Request $request, $identifier)
    {
        if ($this->permissions['browse'] != '' && !\Auth::user()->hasPermissionTo($this->permissions['browse'])) {
            flash(trans('core::core.you_dont_have_access'))->error();
            return redirect()->route($this->routes['index']);
        }

        //return response()->json(Session::all());
        $repository = $this->getRepository();

        $entity = $repository->find($identifier);

        $this->entity = $entity;

        if (empty($entity)) {
            flash(trans('core::core.entity.entity_not_found'))->error();

            return redirect(route($this->routes['index']));
        }

        switch($request['event'])
        {
            case 'signing_complete':
                {
                    $client = new \DocuSign\Rest\Client([
                        'username'       => env('DOCUSIGN_USERNAME'),
                        'password'       => env('DOCUSIGN_PASSWORD'),
                        'integrator_key' => env('DOCUSIGN_INTEGRATOR_KEY')
                    ]);

                    $polizza = PolizzaCar::where('id', $entity->id)->first();
                    //$polizza->update([
                    //    'envelope_id' => $envelope_id // mark as used so it can't be reused
                    //]);

                    $envelope_id = $polizza->envelope_id;

                    //
                    // LIST DOCUMENTS //
                    // $result = $client->envelopes->listDocuments($envelope_id,);

                    $docStream = $client->envelopes->getDocument(1, $envelope_id);
                    // $fullPath = storage_path('signed_pdf/'. $polizza->id .'/');
                    $pdfFile = $polizza->company_name.'_Certificato_Firmato.pdf';
                    $fullPath = storage_path('uploaded_docs/' . $pdfFile );
                    
                    /* if (!\File::exists($fullPath))
                    {
                        \File::makeDirectory($fullPath, 0755, true, true);
                    } */

                    file_put_contents($fullPath, file_get_contents($docStream->getPathname()));
            
                    // return $docStream;
                    $polizza->update([
                        'pdf_signed_contract' => $pdfFile, // set filename to DB
                        'status' => 4
                    ]);
                    

                    dd($polizza);

                    
                    flash('FIRMATO' /* trans('PolizzaCar::PolizzaCar.order_request')*/)->success();
                    // return redirect()->route('polizzacar.polizzacar.show',$polizza->id);
                    

                    


                    // return ['envelope_id' => $envelope_id, 'url' => $result['url']];

                    //return $request['event']. $identifier;
                }
            break;
        }

        exit;

        
        // return Redirect::route('addmoney.paywithpaypal');
    }


   
}

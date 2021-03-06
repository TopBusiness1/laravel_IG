<?php

return [

    'module' => 'Polizza CAR',
    'module_description' => 'Polizza CAR - Assicurazione Convenzione IG',
    'delete' => 'Delete',
    'edit' => 'Edit',

    'create' => 'Create',
    'create_new' => 'Create Polizza CAR',
    'back' => 'Back',
    'details' => 'Details',
    'list' => 'Polizza CAR list',
    'updated' => 'Polizza CAR updated',
    'created' => 'Polizza CAR created',
    'print' => 'Print PDF',
    'quotePDF' => 'Download Quote PDF',
    'CertificatoPDF' => 'Insurance Certification',
    'fast_quote' => 'Fast Quote',
    'sign' => 'Sign and Accept',
    'invite' => 'Send to Contractor',
    'approve' => 'Approve and Invite Contractor',
    'upload_signed_doc' => 'Upload Signed Documents',
    'send_to_contractor' => 'Send to Contractor',

    'request_insurance' => 'Ask Quotation',
    'download_csv' => 'Download CSV',
    'send_order' => 'Send Activation Order',
    'order_request' => 'Order sent to the company. Your Policy will be active soon. Please hold on.',
    'order_sent' => 'Order processed successfully. Your Policy is now active. The Contractor has been notified.',
    'quote_created' => 'Quote request created.',
    'invite_sent_with_login' => 'Contractor invited for the first time. He will set a password before login.',
    'invite_sent' => 'Invite to Contractor Sent',

    'signed_doc_pdf' => 'Signed Contract',
    'payment_proof_pdf' => 'Payment Proof',

    'uploaded_doc_success' => 'Upload Succeeded. Please wait for verification.',

    'copy_from_account' => 'Copy from Account',

    'copy_from_procurement' => 'Copy from Procurement',

    'panel' => [
        'contractor' => 'Contractor',
        'information' => 'Information',
        'procurement' => 'Procurement',
        'warranties' => 'Insured - Guarantees and Premium',
        'contact_data' => 'Contact data',
        'address_information' => 'Address information',
        'signed_pdf' => 'Signed Documents',
        ''
    ],

    'form' => [
        'id' => 'Polizza Number',
        'insurance_policy' => 'Polizza Number',
        'date_request' => 'Date Issue',
        'date_effect' => 'Date Effect',
        'works_duration_mm' => 'Duration',
        'company_name' => 'Company Name',
        'company_vat' => 'PIVA',
        'numero_pratica' => 'Quote Number',
        'numero_polizza' => 'Policy Number',
        'stato' => 'Status',
        'category' => 'Category',
        'procurement' => 'Procurement',
        'category_id' => 'Category',
        'procurement_id' => 'Procurement',
        'company_email' => 'Email',
        'company_phone' => 'Phone',
        'company_address' => 'Address',
        'company_city' => 'City',
        'company_provincia' => 'State',
        'company_cap' => 'Zip code',
        'country_id' => 'Country',
        'country' => 'Country',
        'subject_procurement' => 'Subject Procurement',
        'risk_id' => 'Risk',
        '24_month' => '24 months',
        'mm_24' => '24 months',
        '36_month' => '36 months',
        'mm_36' => '36 months',
        'procurement_total' => 'Totale Procurement',

        'totals' => 'Total',
        'total_labels' => 'PREMIUM AMOUNT',
        'total_gross' => 'Premium Gross',
        'total_net' => 'Premium Net',
        'partite_total' => 'Totale Partite',
        
        'status_id' => 'Status',
        'works_descr' => 'Job Description',
        'works_type_details' => 'Work Type Details',
        'works_type_details_id' => 'Work Type Details',
        'sezione_a' => 'SEZIONE A – DANNI A COSE',
        'partita_1' => 'Partita 1 - OPERE',
        'partita_2' => 'Partita 2 - OPERE PREESISTENTI',
        'partita_3' => 'Partita 3 – DEMOLIZIONE e SGOMBERO',
        'car_limit_amount' => 'Massimale',
        'car_premium_gross' => 'Premium Gross',
        'car_premium_net' => 'Premium Net',
        'car_p1_limit_amount' => 'Value Insured',
        'car_p1_premium_gross' => 'Premium Gross',
        'car_p1_premium_net' => 'Premium Net',
        'car_p2_limit_amount' => 'Amount Insured',
        'car_p2_premium_gross' => 'Premium Gross',
        'car_p2_premium_net' => 'Premium Net',
        'car_p3_limit_amount' => 'Amount Insured',
        'car_p3_premium_gross' => 'Premium Gross',
        'car_p3_premium_net' => 'Premium Net',
        'works_place' => 'Works Place',
        'primary_works_place' => 'Primary Work Place',
        'owned_by' => 'Assigned To',
        'category' => 'Category',
        'sezione_b' => 'SEZIONE B – RESPONSABILITÀ CIVILE TERZI',
        'sezione_b_max' => 'Massimale',
        'car_civil_liability' => ' ',
        'sezione_b_terms' => ' ',
        'tax_rate' => 'Tax Rate',
        'coeff_tariffa'=> 'Coeff Tariffa',
        'commission' => 'Commissions',
        'pdf_signed_contract' => 'Signed Contract',
        'pdf_payment_proof' => 'Payment Proof',
        'name'=> 'Name',
    ],

    'table' => [
    ],

    'settings_top' => 'Anagrafiche',

    'settings' => [
        'category' => 'Category',
        'procurement' => 'Procurement',
        'works_type' => 'Works Type',
        'status' => 'Status',
        'piano_tariffario' => 'Tariff Plan',
    ],
    'category' => [
        'module' => 'Polizza CAR Category',
        'module_description' => 'Category lookup values used in Polizza CAR',

        'panel' => [
            'details' => 'Polizza CAR Category Details',
        ],
    ],
    'works_type' => [
        'module' => 'Polizza CAR Works Type',
        'module_description' => 'Works Type lookup values used in Polizza CAR',

        'panel' => [
            'details' => 'Polizza CAR Works Type',
        ],
    ],
    'piano_tariffario' => [
        'module' => 'Polizza CAR Piano Tariffario',
        'module_description' => ' Piano Tariffario lookup values used in Polizza CAR',

        'panel' => [
            'details' => 'Polizza CAR  Piano Tariffario',
        ],
        'form' => [
            'name' => 'Rischio',
            '24_month' => '24 mesi',
            'mm_24' => '24 mesi',
            '36_month' => '36 mesi',
            'mm_36' => '36 mesi',
            'tax_rate' => 'Tax Rate',
            'coeff_tariffa'=> 'Coeff Tariffa',
            'commission' => 'Commissioni',
        ],
    ],
    'procurement' => [
        'module' => 'Appalti Polizza CAR',
        'module_description' => 'Appalti lookup values used in Polizza CAR',

        'panel' => [
            'details' => 'Appalti Polizza CAR - Details',
            'contractor' => 'Appaltatore',
               'information' => 'Informazioni',
                'procurement' => 'Appalto',
        ],
        'form' => [
            'procurement_id' => 'Appalto',
            'works_duration_mm' => 'Durata (mesi)',
            'company_name' => 'Nome Compagnia',
            'company_vat' => 'PIVA',
            'numero_pratica' => 'Numero Quotazione',
            'numero_polizza' => 'Numero Polizza',
            'stato' => 'Stato',
            'category' => 'Categoria',
            'procurement' => 'Appalto',
            'category_id' => 'Categoria',
            'procurement_id' => 'Appalto',
            'company_email' => 'Email',
            'company_phone' => 'Telefono',
            'company_address' => 'Indirizzo',
            'company_city' => 'Citta',
            'company_provincia' => 'Provincia',
            'company_cap' => 'CAP',
            'country_id' => 'Paese',
            'country' => 'Paese',
            'subject_procurement' => 'Descrizione oggetto appalto',
            'risk_id' => 'Rischio',
            '24_month' => '24 mesi',
            'mm_24' => '24 mesi',
            '36_month' => '36 mesi',
            'mm_36' => '36 mesi',
            'procurement_total' => 'Totale Appalto',
            'total_labels' => 'TOTALE PREMIO',
            'total_gross' => 'Totale Lordo',
            'total_net' => 'Totale Netto',
            'partite_total' => 'Totale Partite',
            'status_id' => 'Stato',
            'works_descr' => 'Descrizione Lavori',
            'works_type_details' => 'Tipologia Lavori',
            'works_type_details_id' => 'Tipologia Lavori',
            'works_place' => 'Provincia di esecuzione',
            'primary_works_place' => 'Luogo di esecuzione',
            'tax_rate' => 'Tax Rate',
            'coeff_tariffa'=> 'Coeff Tariffa',
            'commission' => 'Commissioni',
        ],
    ],
    'status' => [
        'module' => 'Polizza CAR Status',
        'module_description' => 'Status lookup values used in Polizza CAR',

        'panel' => [
            'details' => 'Polizza CAR Status Details',
        ],
        'form' => [
            'name' => 'Status',
            'image' => 'Icon',
            'color' => 'Color',
        ],
        'status_1' => 'Incomplete',
        'status_2' => 'Waiting Approval',
        'status_3' => 'Waiting Signed Doc',
        'status_4' => 'Waiting Verify',
        'status_5' => 'Processing',
        'status_6' => 'Active',
        'status_7' => 'Rejected',
    ],
    'pdf' => [
        'quote' => 'Polizza CAR',
        'created' =>  'Created',
        'valid_until' => 'Valid Until',
        'quotation_for' => 'Quotation for',
        'contact' => 'Contact',
        'email' => 'E-mail',
        'mobile' => 'Mobile',
        'office_phone' => 'Office phone',
        'customer' => 'Customer',
        'sales_representative' => 'Sales representative',

        'product_service' => 'Product / Service',
        'unit_cost' => 'Unit cost',
        'quantity' => 'Quantity',
        'line_total' => 'Line Total',

        'subtotal' => 'Subtotal',
        'discount' => 'Discount',
        'delivery_cost' => 'Delivery cost',
        'tax' => 'Tax',
        'gross_value' => 'Gross Value',
        'notes' => 'Notes',
        'shipping' => 'Shipping',
        'carrier' => 'Carrier',
        'address' => 'Address',
        'phone' => 'Phone',
        'fax' => 'Fax',
        'vat' => 'Tax Number'
    ]

];

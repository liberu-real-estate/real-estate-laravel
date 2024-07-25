<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DocumentTemplate;

class DocumentTemplateSeeder extends Seeder
{
    public function run()
    {
        DocumentTemplate::findOrCreateLeaseTemplate();
        DocumentTemplate::findOrCreateUKASTTemplate();
        DocumentTemplate::findOrCreateSection8Template();
        DocumentTemplate::findOrCreateSection21Template();
        DocumentTemplate::findOrCreateNoticeToEnterTemplate();
        DocumentTemplate::findOrCreateNoticeOfRentIncreaseTemplate();
        DocumentTemplate::findOrCreateTenantWelcomeLetterTemplate();
        DocumentTemplate::findOrCreateGuarantorAgreementTemplate();
    }
}
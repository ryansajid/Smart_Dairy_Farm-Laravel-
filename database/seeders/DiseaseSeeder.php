<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Health\Disease;

class DiseaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $diseases = [
            [
                'name' => 'Foot and Mouth Disease',
                'disease_type' => 'viral',
                'incubation_period_days' => 3,
                'transmission_methods' => 'Direct contact, airborne particles, contaminated feed/water, fomites',
                'clinical_signs' => 'Fever, blister-like lesions on feet and mouth, drooling, lameness, reduced milk production',
                'treatment_protocol' => 'Supportive care, anti-inflammatory drugs, antibiotics for secondary infections, isolation',
                'prevention' => 'Vaccination, strict biosecurity, quarantine of new animals, disinfection',
                'zoonotic' => false,
                'notifiable' => true,
                'severity' => 'critical',
            ],
            [
                'name' => 'Bovine Mastitis',
                'disease_type' => 'bacterial',
                'incubation_period_days' => 2,
                'transmission_methods' => 'Contaminated milking equipment, poor hygiene, environmental exposure',
                'clinical_signs' => 'Swollen udder, abnormal milk ( clots, discoloration), reduced milk yield, fever',
                'treatment_protocol' => 'Antibiotics (intramammary or systemic), anti-inflammatories, supportive care',
                'prevention' => 'Proper milking hygiene, dry cow therapy, regular udder health monitoring',
                'zoonotic' => false,
                'notifiable' => false,
                'severity' => 'high',
            ],
            [
                'name' => 'Brucellosis',
                'disease_type' => 'bacterial',
                'incubation_period_days' => 14,
                'transmission_methods' => 'Ingestion of contaminated materials, contact with aborted fetuses, breeding',
                'clinical_signs' => 'Abortion, stillbirth, weak calves, reduced fertility, lameness',
                'treatment_protocol' => 'No effective treatment - culling of infected animals recommended',
                'prevention' => 'Vaccination, test and cull program, biosecurity, herd certification',
                'zoonotic' => true,
                'notifiable' => true,
                'severity' => 'critical',
            ],
            [
                'name' => 'Bovine Respiratory Disease Complex',
                'disease_type' => 'viral',
                'incubation_period_days' => 5,
                'transmission_methods' => 'Aerosol spread, direct contact, stress-induced immunosuppression',
                'clinical_signs' => 'Coughing, nasal discharge, labored breathing, fever, depression, reduced appetite',
                'treatment_protocol' => 'Antibiotics for bacterial components, anti-inflammatories, supportive care',
                'prevention' => 'Vaccination (IBR, BVD, PI3, BRSV), stress reduction, ventilation improvement',
                'zoonotic' => false,
                'notifiable' => false,
                'severity' => 'high',
            ],
            [
                'name' => 'Paratuberculosis (Johne\'s Disease)',
                'disease_type' => 'bacterial',
                'incubation_period_days' => 365,
                'transmission_methods' => 'Ingestion of contaminated colostrum/milk, fecal-oral route',
                'clinical_signs' => 'Chronic diarrhea, weight loss despite good appetite, decreased milk production',
                'treatment_protocol' => 'No cure - culling of infected animals, management of herd test',
                'prevention' => 'Test and cull, colostrum management, calf isolation, hygiene',
                'zoonotic' => false,
                'notifiable' => false,
                'severity' => 'high',
            ],
            [
                'name' => 'Bovine Tuberculosis',
                'disease_type' => 'bacterial',
                'incubation_period_days' => 60,
                'transmission_methods' => 'Aerosol, ingestion, direct contact with infected animals',
                'clinical_signs' => 'Chronic cough, weight loss, enlarged lymph nodes, decreased appetite',
                'treatment_protocol' => 'No treatment - mandatory culling and herd testing',
                'prevention' => 'Test and slaughter, pasteurization of milk, biosecurity',
                'zoonotic' => true,
                'notifiable' => true,
                'severity' => 'critical',
            ],
            [
                'name' => 'Internal Parasites (Worms)',
                'disease_type' => 'parasitic',
                'incubation_period_days' => 21,
                'transmission_methods' => 'Ingestion of larvae from pasture, contaminated feed',
                'clinical_signs' => 'Diarrhea, weight loss, poor coat, anemia, bottle jaw, decreased production',
                'treatment_protocol' => 'Anthelmintic dewormers, supportive care for severe cases',
                'prevention' => 'Strategic deworming, pasture management, rotational grazing, fecal monitoring',
                'zoonotic' => false,
                'notifiable' => false,
                'severity' => 'medium',
            ],
            [
                'name' => 'Ketosis',
                'disease_type' => 'metabolic',
                'incubation_period_days' => 1,
                'transmission_methods' => 'Not infectious - metabolic disorder related to negative energy balance',
                'clinical_signs' => 'Reduced appetite, decreased milk production, sweet-smelling breath, weight loss',
                'treatment_protocol' => 'Glucose administration (IV or oral), propylene glycol, corticosteroids',
                'prevention' => 'Proper dry cow management, adequate energy intake pre-calving, body condition scoring',
                'zoonotic' => false,
                'notifiable' => false,
                'severity' => 'high',
            ],
            [
                'name' => 'Milk Fever (Hypocalcemia)',
                'disease_type' => 'nutritional',
                'incubation_period_days' => 1,
                'transmission_methods' => 'Not infectious - metabolic disorder due to calcium deficiency',
                'clinical_signs' => 'Muscle tremors, weakness, cold ears, down cow, bloating, respiratory failure',
                'treatment_protocol' => 'IV calcium borogluconate, calcium supplements, supportive care',
                'prevention' => 'Dietary management in dry period, anionic salts, vitamin D supplementation',
                'zoonotic' => false,
                'notifiable' => false,
                'severity' => 'critical',
            ],
            [
                'name' => 'Lumpy Skin Disease',
                'disease_type' => 'viral',
                'incubation_period_days' => 7,
                'transmission_methods' => 'Insect vectors (mosquitoes, ticks), direct contact, contaminated equipment',
                'clinical_signs' => 'Fever, skin nodules (2-5cm), enlarged lymph nodes, reduced milk production, abortion',
                'treatment_protocol' => 'Supportive care, antibiotics for secondary infections, vector control',
                'prevention' => 'Vaccination, vector control, isolation of affected animals',
                'zoonotic' => false,
                'notifiable' => true,
                'severity' => 'critical',
            ],
        ];

        foreach ($diseases as $disease) {
            Disease::create($disease);
        }

        $this->command->info('Disease seeder completed. ' . count($diseases) . ' diseases seeded.');
    }
}

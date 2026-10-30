<?php

namespace Database\Seeders;

use App\Models\Election;
use App\Models\PollingStation;
use App\Models\Voter;
use App\Models\VoterPhoto;
use App\Models\Ward;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class WardOneVoterSeeder extends Seeder
{
    // Booth to gram panchayat ward mapping from the booth table.
    private const BOOTHS = [
        ['booth' => '84', 'room' => '01', 'wards' => [1, 2, 3]],
        ['booth' => '85', 'room' => '02', 'wards' => [4, 5, 9]],
        ['booth' => '86', 'room' => '03', 'wards' => [6, 7, 8]],
        ['booth' => '87', 'room' => '04', 'wards' => [10, 15, 16]],
    ];

    public function run(): void
    {
        $election = Election::updateOrCreate(
            ['district' => 'Kotputli-Behror', 'panchayat_samiti' => 'Neemrana', 'year' => 2026],
            [
                'name' => 'Panchayat Samiti Neemrana, Dist. Kotputli Behror',
                'election_type' => 'Panchayat',
                'gram_panchayat' => 'Shahjahanpur',
                'assembly_constituency' => '61-Mundawar',
                'is_active' => true,
            ]
        );

        $wardIds = [];
        foreach (self::BOOTHS as $row) {
            foreach ($row['wards'] as $wardNumber) {
                $ward = Ward::firstOrCreate(
                    ['election_id' => $election->id, 'ward_number' => (string) $wardNumber],
                    ['village_name' => 'Shahjahanpur']
                );
                $wardIds[$wardNumber] = $ward->id;

                PollingStation::updateOrCreate(
                    [
                        'election_id' => $election->id,
                        'ward_id' => $ward->id,
                        'booth_number' => $row['booth'],
                    ],
                    [
                        'polling_station_name' => 'राजकीय उच्च माध्यमिक विद्यालय, शाहजहांपुर',
                        'room_number' => $row['room'],
                        'address' => 'राजकीय उच्च माध्यमिक विद्यालय, शाहजहांपुर',
                    ]
                );
            }
        }

        $records = json_decode(File::get(database_path('data/ward1_voters.json')), true);
        $photoDir = public_path('voter-photos/ward1');
        File::ensureDirectoryExists($photoDir);

        foreach ($records as $record) {
            $wardId = $wardIds[(int) $record['ward_number']];
            $station = PollingStation::where([
                'election_id' => $election->id,
                'ward_id' => $wardId,
                'booth_number' => $record['booth_number'],
            ])->firstOrFail();

            $voter = Voter::updateOrCreate(
                ['election_id' => $election->id, 'ward_id' => $wardId, 'serial_number' => $record['serial']],
                [
                    'ward_id' => $wardId,
                    'polling_station_id' => $station->id,
                    'epic_number' => $record['epic'],
                    'name' => $record['name'],
                    'relative_name' => $record['relative_name'],
                    'relation_type' => $record['relation_type'],
                    'house_number' => $record['house_number'],
                    'locality' => $record['section'],
                    'age' => $record['age'],
                    'gender' => $record['gender'],
                    'part_number' => $record['ward_number'],
                    'status' => $record['flag'] ?: null,
                ]
            );

            if ($record['photo_base64']) {
                $relative = 'voter-photos/ward1/'.$record['serial'].'.png';
                File::put(public_path($relative), base64_decode($record['photo_base64']));

                VoterPhoto::updateOrCreate(
                    ['voter_id' => $voter->id],
                    [
                        'photo_path' => $relative,
                        'original_filename' => $record['serial'].'.png',
                        'mime_type' => 'image/png',
                        'is_primary' => true,
                    ]
                );
            }
        }
    }
}

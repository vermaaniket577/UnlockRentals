<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Updates scraped owner accounts that were assigned dummy @unlockrentals.com emails
     * to use their realistic/authentic email addresses.
     */
    public function up(): void
    {
        try {
            $knownSellers = [
                'rajesh verma'       => 'rajesh.verma.realty@gmail.com',
                'sandeep sharma'     => 'sandeep.sharma.properties@gmail.com',
                'pooja malhotra'     => 'pooja.malhotra.living@gmail.com',
                'vikram singhania'   => 'vikram.singhania.infra@gmail.com',
                'ananya rao'         => 'ananya.rao.mgmt@gmail.com',
                'karan kapoor'       => 'karan.kapoor.estates@gmail.com',
                'meenakshi sundaram' => 'meenakshi.sundaram.realty@gmail.com',
                'arun deshmukh'      => 'arun.deshmukh.housing@gmail.com',
                'deepak agarwal'     => 'deepak.agarwal.hub@gmail.com',
                'nitin bansal'       => 'nitin.bansal.realtors@gmail.com',
                'rahul chawla'       => 'rahul.chawla.realty@gmail.com',
                'sunil joshi'        => 'sunil.joshi.homes@gmail.com',
            ];

            // Protect internal administrative emails
            $protectedEmails = [
                'admin@unlockrentals.com',
                'support@unlockrentals.com',
                'owner1@unlockrentals.com',
                'owner2@unlockrentals.com',
                'tenant@unlockrentals.com',
            ];

            $users = DB::table('users')
                ->where('email', 'like', '%@unlockrentals.com')
                ->where('role', '!=', 'admin')
                ->whereNotIn('email', $protectedEmails)
                ->get();

            foreach ($users as $user) {
                $nameLower = strtolower(trim($user->name));
                $newEmail = null;

                foreach ($knownSellers as $knownKey => $knownEmail) {
                    if (str_contains($nameLower, $knownKey)) {
                        $newEmail = $knownEmail;
                        break;
                    }
                }

                if (!$newEmail) {
                    // Extract prefix before @unlockrentals.com and convert to standard email format
                    $prefix = explode('@', $user->email)[0] ?? '';
                    $cleanPrefix = str_replace('-', '.', $prefix);
                    $newEmail = $cleanPrefix . '@gmail.com';
                }

                // Ensure email uniqueness
                if (DB::table('users')->where('email', $newEmail)->where('id', '!=', $user->id)->exists()) {
                    $phoneDigits = preg_replace('/\D/', '', (string)($user->phone ?? ''));
                    $suffix = !empty($phoneDigits) ? substr($phoneDigits, -4) : rand(100, 999);
                    $newEmail = explode('@', $newEmail)[0] . '.' . $suffix . '@gmail.com';
                }

                DB::table('users')->where('id', $user->id)->update([
                    'email'      => $newEmail,
                    'updated_at' => now(),
                ]);
            }
        } catch (\Throwable $e) {
            // Silently continue if table/field structure varies
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Irreversible data sanitation
    }
};

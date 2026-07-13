<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Helper mapping for all country name to expected TRON dial codes.
     */
    private function getDialCode(string $country): ?string
    {
        $allCountries = [
            "Afghanistan" => "93", "Albania" => "355", "Algeria" => "213", "American Samoa" => "1684",
            "Andorra" => "376", "Angola" => "244", "Anguilla" => "1264", "Antioquia" => "",
            "Antigua and Barbuda" => "1268", "Argentina" => "54", "Armenia" => "374", "Aruba" => "297",
            "Australia" => "61", "Austria" => "43", "Azerbaijan" => "994", "Bahamas" => "1242",
            "Bahrain" => "973", "Bangladesh" => "880", "Barbados" => "1246", "Belarus" => "375",
            "Belgium" => "32", "Belize" => "501", "Benin" => "229", "Bermuda" => "1441",
            "Bhutan" => "975", "Bolivia" => "591", "Bosnia and Herzegovina" => "387", "Botswana" => "267",
            "Brazil" => "55", "Brunei" => "673", "Bulgaria" => "359", "Burkina Faso" => "226", "Burundi" => "257",
            "Cambodia" => "855", "Cameroon" => "237", "Canada" => "1", "Cape Verde" => "238",
            "Chile" => "56", "China" => "86", "Colombia" => "57", "Comoros" => "269", "Congo" => "242", 
            "Costa Rica" => "506", "Croatia" => "385", "Cuba" => "53", "Cyprus" => "357", "Czechia" => "420", 
            "DR Congo" => "243", "Denmark" => "45", "Djibouti" => "253", "Dominica" => "1767", 
            "Dominican Republic" => "1809", "Ecuador" => "593", "Egypt" => "20", "El Salvador" => "503", 
            "Equatorial Guinea" => "240", "Eritrea" => "291", "Estonia" => "372", "Eswatini" => "268", 
            "Ethiopia" => "251", "Fiji" => "679", "Finland" => "358", "France" => "33", "Gabon" => "241", 
            "Gambia" => "220", "Georgia" => "995", "Germany" => "49", "Ghana" => "233", "Greece" => "30", 
            "Guatemala" => "502", "Guinea" => "224", "Haiti" => "509", "Honduras" => "504", "Hong Kong" => "852", 
            "Hungary" => "36", "Iceland" => "354", "India" => "91", "Indonesia" => "62", "Iran" => "98", 
            "Iraq" => "964", "Ireland" => "353", "Israel" => "972", "Italy" => "39", "Ivory Coast" => "225", 
            "Jamaica" => "1876", "Japan" => "81", "Jordan" => "962", "Kazakhstan" => "7", "Kenya" => "254", 
            "Kuwait" => "965", "Kyrgyzstan" => "996", "Laos" => "856", "Latvia" => "371", "Lebanon" => "961", 
            "Lesotho" => "266", "Liberia" => "231", "Libya" => "218", "Liechtenstein" => "423", 
            "Lithuania" => "370", "Luxembourg" => "352", "Macau" => "853", "Madagascar" => "261", 
            "Malawi" => "265", "Malaysia" => "60", "Maldives" => "960", "Mali" => "223", "Malta" => "356", 
            "Mexico" => "52", "Moldova" => "373", "Monaco" => "377", "Mongolia" => "976", "Montenegro" => "382", 
            "Morocco" => "212", "Mozambique" => "258", "Myanmar" => "95", "Namibia" => "264", "Nepal" => "977", 
            "Netherlands" => "31", "New Zealand" => "64", "Nicaragua" => "505", "Niger" => "227", 
            "Nigeria" => "234", "Norway" => "47", "Oman" => "968", "Pakistan" => "92", "Panama" => "507", 
            "Paraguay" => "595", "Peru" => "51", "Philippines" => "63", "Poland" => "48", "Portugal" => "351", 
            "Qatar" => "974", "Romania" => "40", "Russia" => "7", "Rwanda" => "250", "Saudi Arabia" => "966", 
            "Senegal" => "221", "Serbia" => "381", "Singapore" => "65", "Slovakia" => "421", "Slovenia" => "386", 
            "Somalia" => "252", "South Africa" => "27", "South Korea" => "82", "South Sudan" => "211", 
            "Spain" => "34", "Sri Lanka" => "94", "Sudan" => "249", "Sweden" => "46", "Switzerland" => "41", 
            "Syria" => "963", "Taiwan" => "886", "Tajikistan" => "992", "Tanzania" => "255", "Thailand" => "66", 
            "Togo" => "228", "Tonga" => "676", "Tunisia" => "216", "Turkey" => "90", "Türkiye" => "90", 
            "Uganda" => "256", "Ukraine" => "380", "United Arab Emirates" => "971", "United Kingdom" => "44", 
            "United States" => "1", "Uruguay" => "598", "Uzbekistan" => "998", "Venezuela" => "58", 
            "Vietnam" => "84", "Yemen" => "967", "Zambia" => "260", "Zimbabwe" => "263"
        ];

        return $allCountries[$country] ?? null;
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        $validator = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            // 'referee_id' => 'required',
            'country' => 'required',
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ]);

        // Validate that phone number aligns with the chosen country dial code
        $validator->after(function ($validator) use ($input) {
            $country = $input['country'] ?? '';
            $phone = trim($input['phone'] ?? '');
            
            if ($country !== '' && $phone !== '') {
                $dialCode = $this->getDialCode($country);
                if ($dialCode) {
                    // Strip non-digits from the phone number
                    $cleanPhone = preg_replace('/\D/', '', $phone);
                    if (!str_starts_with($cleanPhone, $dialCode)) {
                        $validator->errors()->add('phone', "The phone number must match the selected country's dialing code (+{$dialCode}).");
                    }
                }
            }
        });

        $validator->validate();
  
        $settings = \App\Models\WithdrawalSetting::current();
        $has_free = ($settings && $settings->allow_free_dashboard_access) ? 'yes' : 'no';
        $paid_pkg = ($settings && $settings->allow_free_dashboard_access) ? 'standard' : 'no';
        $contract_signed = ($settings && $settings->allow_free_dashboard_access) ? 'Signed' : '';

        // Generate 6-digit Activation PIN Code
        $pin = (string) rand(100000, 999999);

        // Generate 7-digit Transfer Code Number
        $transferCode = (string) rand(1000000, 9999999);

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'phone' => $input['phone'],
            'referee_id' => $input['referee_id'] ?? null,
            'country' => $input['country'],
            'password' => Hash::make($input['password']),
            'has_free_package' => $has_free,
            'has_paid_package' => $paid_pkg,
            'contract' => $contract_signed,
            'email_verification_pin' => $pin,
            'transfer_code' => $transferCode,
        ]);

        return $user;
    }
}

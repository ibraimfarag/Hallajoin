{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.min.css"> --}}

<style>
    .select-box {
        position: relative;

        width: 26rem;
        /* margin: 7rem auto; */
    }

    .select-box input {
        width: 100%;
        padding: 1rem .6rem;
        font-size: 1.1rem;

        border: .1rem solid transparent;
        outline: none;
    }

    input[type="tel"] {
        border-radius: 0 .5rem .5rem 0;
    }

    .select-box input:focus {
        border: .1rem solid var(--primary);
    }

    .selected-option {
        background-color: #eee;
        border-radius: .5rem;
        overflow: hidden;

        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .selected-option div {
        position: relative;

        width: 8rem;
        padding: 0 2.8rem 0 .5rem;
        text-align: center;
        cursor: pointer;
    }

    .selected-option div::after {
        position: absolute;
        content: "";
        right: .8rem;
        top: 50%;
        transform: translateY(-50%) rotate(45deg);

        width: .8rem;
        height: .8rem;
        border-right: .12rem solid var(--primary);
        border-bottom: .12rem solid var(--primary);

        transition: .2s;
    }

    .selected-option div.active::after {
        transform: translateY(-50%) rotate(225deg);
    }

    .select-box .options {
        position: absolute;
        top: 4rem;

        width: 100%;
        background-color: #fff;
        border-radius: .5rem;
        z-index: 99999;
        display: none;
    }

    .select-box .options.active {
        display: block;
    }

    .select-box .options::before {
        position: absolute;
        content: "";
        left: 1rem;
        top: -1.2rem;

        width: 0;
        height: 0;
        border: .6rem solid transparent;
        border-bottom-color: var(--primary);
    }

    input.search-box {
        /* background-color: var(--primary); */
        color: #fff;
        border-radius: .5rem .5rem 0 0;
        padding: 1.4rem 1rem;
    }

    .select-box ol {
        list-style: none;
        max-height: 23rem;
        overflow: overlay;
    }

    .select-box ol::-webkit-scrollbar {
        width: 0.6rem;
    }

    .select-box ol::-webkit-scrollbar-thumb {
        width: 0.4rem;
        height: 3rem;
        background-color: #ccc;
        border-radius: .4rem;
    }

    .select-box ol li {
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        cursor: pointer;
    }

    .select-box ol li.hide {
        display: none;
    }

    .select-box ol li:not(:last-child) {
        border-bottom: .1rem solid #eee;
    }

    .select-box ol li:hover {
        background-color: lightcyan;
    }

    .select-box ol li .country-name {
        margin-left: .4rem;
    }
</style>

<form class="form bravo-form-register" method="post" action="{{ route('auth.register.store') }}">
    @csrf
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <input type="text" class="form-control" name="first_name" autocomplete="off"
                    placeholder="{{ __('First Name') }}">
                <i class="input-icon field-icon icofont-waiter-alt"></i>
                <span class="invalid-feedback error error-first_name"></span>
            </div>
        </div>
        <div class="col-lg-6 col-md-12">
            <div class="form-group">
                <input type="text" class="form-control" name="last_name" autocomplete="off"
                    placeholder="{{ __('Last Name') }}">
                <i class="input-icon field-icon icofont-waiter-alt"></i>
                <span class="invalid-feedback error error-last_name"></span>
            </div>
        </div>
    </div>


    <div class="form-group">
        <div class="select-box">
            <div class="selected-option">
                <div>
                    <span class="iconify" data-icon="flag:gb-4x3"></span>
                    <strong>+44</strong>
                </div>
                <input type="tel"  class="form-control" name="phone" autocomplete="off"
                    placeholder="Phone">
                    
                </div>
                <div class="options">
                    <input type="text" class="search-box" placeholder="Search Country Name">
                    <ol>
                        
                    </ol>
                </div>
            </div>
            <span class="invalid-feedback error error-phone"></span>
    </div>
    <div class="form-group">
        <input type="email" class="form-control" name="email" autocomplete="off"
            placeholder="{{ __('Email address') }}">
        <i class="input-icon field-icon icofont-mail"></i>
        <span class="invalid-feedback error error-email"></span>
    </div>
    <div class="form-group">
        <input type="password" class="form-control" name="password" autocomplete="off"
            placeholder="{{ __('Password') }}" id="passwordField">
        <i class="input-icon field-icon icofont-eye" id="togglePassword"></i>
        <span class="invalid-feedback error error-password"></span>
    </div>
    <div class="form-group">
        <label for="term">
            <input id="term" type="checkbox" name="term" class="mr5">
            {!! __("I have read and accept the <a href=':link' target='_blank'>Terms and Privacy Policy</a>", [
                'link' => get_page_url(setting_item('booking_term_conditions')),
            ]) !!}
            <span class="checkmark fcheckbox"></span>
        </label>
        <div><span class="invalid-feedback error error-term"></span></div>
    </div>
    @if (setting_item('user_enable_register_recaptcha'))
        <div class="form-group">
            {{ recaptcha_field($captcha_action ?? 'register') }}
        </div>
        <div><span class="invalid-feedback error error-g-recaptcha-response"></span></div>
    @endif
    <div class="error message-error invalid-feedback"></div>
    <div class="form-group">
        <button type="submit" class="btn btn-primary form-submit">
            {{ __('Sign Up') }}
            <span class="spinner-grow spinner-grow-sm icon-loading" role="status" aria-hidden="true"></span>
        </button>
    </div>
    @if (setting_item('facebook_enable') or setting_item('google_enable') or setting_item('twitter_enable'))
        <div class="advanced">
            <p class="text-center f14 c-grey">{{ __('or continue with') }}</p>
            <div class="row">
                @if (setting_item('facebook_enable'))
                    <div class="col-xs-12 col-sm-4">
                        <a href="{{ url('/social-login/facebook') }}" class="btn btn_login_fb_link"
                            data-channel="facebook">
                            <i class="input-icon fa fa-facebook"></i>
                            {{ __('Facebook') }}
                        </a>
                    </div>
                @endif
                @if (setting_item('google_enable'))
                    <div class="col-xs-12 col-sm-4">
                        <a href="{{ url('social-login/google') }}" class="btn btn_login_gg_link" data-channel="google">
                            <i class="input-icon fa fa-google"></i>
                            {{ __('Google') }}
                        </a>
                    </div>
                @endif
                @if (setting_item('twitter_enable'))
                    <div class="col-xs-12 col-sm-4">
                        <a href="{{ url('social-login/twitter') }}" class="btn btn_login_tw_link"
                            data-channel="twitter">
                            <i class="input-icon fa fa-twitter"></i>
                            {{ __('Twitter') }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
    <div class="c-grey f14 text-center">
        {{ __(' Already have an account?') }}
        <a href="#" data-target="#login" data-toggle="modal">{{ __('Log In') }}</a>
    </div>
</form>

<script>
    // JavaScript to toggle password visibility
    const passwordField = document.getElementById('passwordField');
    const togglePassword = document.getElementById('togglePassword');

    togglePassword.addEventListener('click', function() {
        const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordField.setAttribute('type', type);
        // Toggle eye icon
        if (type === 'password') {
            togglePassword.classList.add('icofont-eye');
            togglePassword.classList.remove('icofont-eye-blocked');
        } else {
            togglePassword.classList.remove('icofont-eye');
            togglePassword.classList.add('icofont-eye-blocked');
        }
    });

    
</script>
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script> --}}

<script>
    const countries = [{
                name: "Afghanistan",
                code: "AF",
                phone: 93
            },
            {
                name: "Aland Islands",
                code: "AX",
                phone: 358
            },
            {
                name: "Albania",
                code: "AL",
                phone: 355
            },
            {
                name: "Algeria",
                code: "DZ",
                phone: 213
            },
            {
                name: "American Samoa",
                code: "AS",
                phone: 1684
            },
            {
                name: "Andorra",
                code: "AD",
                phone: 376
            },
            {
                name: "Angola",
                code: "AO",
                phone: 244
            },
            {
                name: "Anguilla",
                code: "AI",
                phone: 1264
            },
            {
                name: "Antarctica",
                code: "AQ",
                phone: 672
            },
            {
                name: "Antigua and Barbuda",
                code: "AG",
                phone: 1268
            },
            {
                name: "Argentina",
                code: "AR",
                phone: 54
            },
            {
                name: "Armenia",
                code: "AM",
                phone: 374
            },
            {
                name: "Aruba",
                code: "AW",
                phone: 297
            },
            {
                name: "Australia",
                code: "AU",
                phone: 61
            },
            {
                name: "Austria",
                code: "AT",
                phone: 43
            },
            {
                name: "Azerbaijan",
                code: "AZ",
                phone: 994
            },
            {
                name: "Bahamas",
                code: "BS",
                phone: 1242
            },
            {
                name: "Bahrain",
                code: "BH",
                phone: 973
            },
            {
                name: "Bangladesh",
                code: "BD",
                phone: 880
            },
            {
                name: "Barbados",
                code: "BB",
                phone: 1246
            },
            {
                name: "Belarus",
                code: "BY",
                phone: 375
            },
            {
                name: "Belgium",
                code: "BE",
                phone: 32
            },
            {
                name: "Belize",
                code: "BZ",
                phone: 501
            },
            {
                name: "Benin",
                code: "BJ",
                phone: 229
            },
            {
                name: "Bermuda",
                code: "BM",
                phone: 1441
            },
            {
                name: "Bhutan",
                code: "BT",
                phone: 975
            },
            {
                name: "Bolivia",
                code: "BO",
                phone: 591
            },
            {
                name: "Bonaire, Sint Eustatius and Saba",
                code: "BQ",
                phone: 599
            },
            {
                name: "Bosnia and Herzegovina",
                code: "BA",
                phone: 387
            },
            {
                name: "Botswana",
                code: "BW",
                phone: 267
            },
            {
                name: "Bouvet Island",
                code: "BV",
                phone: 55
            },
            {
                name: "Brazil",
                code: "BR",
                phone: 55
            },
            {
                name: "British Indian Ocean Territory",
                code: "IO",
                phone: 246
            },
            {
                name: "Brunei Darussalam",
                code: "BN",
                phone: 673
            },
            {
                name: "Bulgaria",
                code: "BG",
                phone: 359
            },
            {
                name: "Burkina Faso",
                code: "BF",
                phone: 226
            },
            {
                name: "Burundi",
                code: "BI",
                phone: 257
            },
            {
                name: "Cambodia",
                code: "KH",
                phone: 855
            },
            {
                name: "Cameroon",
                code: "CM",
                phone: 237
            },
            {
                name: "Canada",
                code: "CA",
                phone: 1
            },
            {
                name: "Cape Verde",
                code: "CV",
                phone: 238
            },
            {
                name: "Cayman Islands",
                code: "KY",
                phone: 1345
            },
            {
                name: "Central African Republic",
                code: "CF",
                phone: 236
            },
            {
                name: "Chad",
                code: "TD",
                phone: 235
            },
            {
                name: "Chile",
                code: "CL",
                phone: 56
            },
            {
                name: "China",
                code: "CN",
                phone: 86
            },
            {
                name: "Christmas Island",
                code: "CX",
                phone: 61
            },
            {
                name: "Cocos (Keeling) Islands",
                code: "CC",
                phone: 672
            },
            {
                name: "Colombia",
                code: "CO",
                phone: 57
            },
            {
                name: "Comoros",
                code: "KM",
                phone: 269
            },
            {
                name: "Congo",
                code: "CG",
                phone: 242
            },
            {
                name: "Congo, Democratic Republic of the Congo",
                code: "CD",
                phone: 242
            },
            {
                name: "Cook Islands",
                code: "CK",
                phone: 682
            },
            {
                name: "Costa Rica",
                code: "CR",
                phone: 506
            },
            {
                name: "Cote D'Ivoire",
                code: "CI",
                phone: 225
            },
            {
                name: "Croatia",
                code: "HR",
                phone: 385
            },
            {
                name: "Cuba",
                code: "CU",
                phone: 53
            },
            {
                name: "Curacao",
                code: "CW",
                phone: 599
            },
            {
                name: "Cyprus",
                code: "CY",
                phone: 357
            },
            {
                name: "Czech Republic",
                code: "CZ",
                phone: 420
            },
            {
                name: "Denmark",
                code: "DK",
                phone: 45
            },
            {
                name: "Djibouti",
                code: "DJ",
                phone: 253
            },
            {
                name: "Dominica",
                code: "DM",
                phone: 1767
            },
            {
                name: "Dominican Republic",
                code: "DO",
                phone: 1809
            },
            {
                name: "Ecuador",
                code: "EC",
                phone: 593
            },
            {
                name: "Egypt",
                code: "EG",
                phone: 20
            },
            {
                name: "El Salvador",
                code: "SV",
                phone: 503
            },
            {
                name: "Equatorial Guinea",
                code: "GQ",
                phone: 240
            },
            {
                name: "Eritrea",
                code: "ER",
                phone: 291
            },
            {
                name: "Estonia",
                code: "EE",
                phone: 372
            },
            {
                name: "Ethiopia",
                code: "ET",
                phone: 251
            },
            {
                name: "Falkland Islands (Malvinas)",
                code: "FK",
                phone: 500
            },
            {
                name: "Faroe Islands",
                code: "FO",
                phone: 298
            },
            {
                name: "Fiji",
                code: "FJ",
                phone: 679
            },
            {
                name: "Finland",
                code: "FI",
                phone: 358
            },
            {
                name: "France",
                code: "FR",
                phone: 33
            },
            {
                name: "French Guiana",
                code: "GF",
                phone: 594
            },
            {
                name: "French Polynesia",
                code: "PF",
                phone: 689
            },
            {
                name: "French Southern Territories",
                code: "TF",
                phone: 262
            },
            {
                name: "Gabon",
                code: "GA",
                phone: 241
            },
            {
                name: "Gambia",
                code: "GM",
                phone: 220
            },
            {
                name: "Georgia",
                code: "GE",
                phone: 995
            },
            {
                name: "Germany",
                code: "DE",
                phone: 49
            },
            {
                name: "Ghana",
                code: "GH",
                phone: 233
            },
            {
                name: "Gibraltar",
                code: "GI",
                phone: 350
            },
            {
                name: "Greece",
                code: "GR",
                phone: 30
            },
            {
                name: "Greenland",
                code: "GL",
                phone: 299
            },
            {
                name: "Grenada",
                code: "GD",
                phone: 1473
            },
            {
                name: "Guadeloupe",
                code: "GP",
                phone: 590
            },
            {
                name: "Guam",
                code: "GU",
                phone: 1671
            },
            {
                name: "Guatemala",
                code: "GT",
                phone: 502
            },
            {
                name: "Guernsey",
                code: "GG",
                phone: 44
            },
            {
                name: "Guinea",
                code: "GN",
                phone: 224
            },
            {
                name: "Guinea-Bissau",
                code: "GW",
                phone: 245
            },
            {
                name: "Guyana",
                code: "GY",
                phone: 592
            },
            {
                name: "Haiti",
                code: "HT",
                phone: 509
            },
            {
                name: "Heard Island and McDonald Islands",
                code: "HM",
                phone: 0
            },
            {
                name: "Holy See (Vatican City State)",
                code: "VA",
                phone: 39
            },
            {
                name: "Honduras",
                code: "HN",
                phone: 504
            },
            {
                name: "Hong Kong",
                code: "HK",
                phone: 852
            },
            {
                name: "Hungary",
                code: "HU",
                phone: 36
            },
            {
                name: "Iceland",
                code: "IS",
                phone: 354
            },
            {
                name: "India",
                code: "IN",
                phone: 91
            },
            {
                name: "Indonesia",
                code: "ID",
                phone: 62
            },
            {
                name: "Iran, Islamic Republic of",
                code: "IR",
                phone: 98
            },
            {
                name: "Iraq",
                code: "IQ",
                phone: 964
            },
            {
                name: "Ireland",
                code: "IE",
                phone: 353
            },
            {
                name: "Isle of Man",
                code: "IM",
                phone: 44
            },
            {
                name: "Israel",
                code: "IL",
                phone: 972
            },
            {
                name: "Italy",
                code: "IT",
                phone: 39
            },
            {
                name: "Jamaica",
                code: "JM",
                phone: 1876
            },
            {
                name: "Japan",
                code: "JP",
                phone: 81
            },
            {
                name: "Jersey",
                code: "JE",
                phone: 44
            },
            {
                name: "Jordan",
                code: "JO",
                phone: 962
            },
            {
                name: "Kazakhstan",
                code: "KZ",
                phone: 7
            },
            {
                name: "Kenya",
                code: "KE",
                phone: 254
            },
            {
                name: "Kiribati",
                code: "KI",
                phone: 686
            },
            {
                name: "Korea, Democratic People's Republic of",
                code: "KP",
                phone: 850
            },
            {
                name: "Korea, Republic of",
                code: "KR",
                phone: 82
            },
            {
                name: "Kosovo",
                code: "XK",
                phone: 383
            },
            {
                name: "Kuwait",
                code: "KW",
                phone: 965
            },
            {
                name: "Kyrgyzstan",
                code: "KG",
                phone: 996
            },
            {
                name: "Lao People's Democratic Republic",
                code: "LA",
                phone: 856
            },
            {
                name: "Latvia",
                code: "LV",
                phone: 371
            },
            {
                name: "Lebanon",
                code: "LB",
                phone: 961
            },
            {
                name: "Lesotho",
                code: "LS",
                phone: 266
            },
            {
                name: "Liberia",
                code: "LR",
                phone: 231
            },
            {
                name: "Libyan Arab Jamahiriya",
                code: "LY",
                phone: 218
            },
            {
                name: "Liechtenstein",
                code: "LI",
                phone: 423
            },
            {
                name: "Lithuania",
                code: "LT",
                phone: 370
            },
            {
                name: "Luxembourg",
                code: "LU",
                phone: 352
            },
            {
                name: "Macao",
                code: "MO",
                phone: 853
            },
            {
                name: "Macedonia, the Former Yugoslav Republic of",
                code: "MK",
                phone: 389
            },
            {
                name: "Madagascar",
                code: "MG",
                phone: 261
            },
            {
                name: "Malawi",
                code: "MW",
                phone: 265
            },
            {
                name: "Malaysia",
                code: "MY",
                phone: 60
            },
            {
                name: "Maldives",
                code: "MV",
                phone: 960
            },
            {
                name: "Mali",
                code: "ML",
                phone: 223
            },
            {
                name: "Malta",
                code: "MT",
                phone: 356
            },
            {
                name: "Marshall Islands",
                code: "MH",
                phone: 692
            },
            {
                name: "Martinique",
                code: "MQ",
                phone: 596
            },
            {
                name: "Mauritania",
                code: "MR",
                phone: 222
            },
            {
                name: "Mauritius",
                code: "MU",
                phone: 230
            },
            {
                name: "Mayotte",
                code: "YT",
                phone: 262
            },
            {
                name: "Mexico",
                code: "MX",
                phone: 52
            },
            {
                name: "Micronesia, Federated States of",
                code: "FM",
                phone: 691
            },
            {
                name: "Moldova, Republic of",
                code: "MD",
                phone: 373
            },
            {
                name: "Monaco",
                code: "MC",
                phone: 377
            },
            {
                name: "Mongolia",
                code: "MN",
                phone: 976
            },
            {
                name: "Montenegro",
                code: "ME",
                phone: 382
            },
            {
                name: "Montserrat",
                code: "MS",
                phone: 1664
            },
            {
                name: "Morocco",
                code: "MA",
                phone: 212
            },
            {
                name: "Mozambique",
                code: "MZ",
                phone: 258
            },
            {
                name: "Myanmar",
                code: "MM",
                phone: 95
            },
            {
                name: "Namibia",
                code: "NA",
                phone: 264
            },
            {
                name: "Nauru",
                code: "NR",
                phone: 674
            },
            {
                name: "Nepal",
                code: "NP",
                phone: 977
            },
            {
                name: "Netherlands",
                code: "NL",
                phone: 31
            },
            {
                name: "Netherlands Antilles",
                code: "AN",
                phone: 599
            },
            {
                name: "New Caledonia",
                code: "NC",
                phone: 687
            },
            {
                name: "New Zealand",
                code: "NZ",
                phone: 64
            },
            {
                name: "Nicaragua",
                code: "NI",
                phone: 505
            },
            {
                name: "Niger",
                code: "NE",
                phone: 227
            },
            {
                name: "Nigeria",
                code: "NG",
                phone: 234
            },
            {
                name: "Niue",
                code: "NU",
                phone: 683
            },
            {
                name: "Norfolk Island",
                code: "NF",
                phone: 672
            },
            {
                name: "Northern Mariana Islands",
                code: "MP",
                phone: 1670
            },
            {
                name: "Norway",
                code: "NO",
                phone: 47
            },
            {
                name: "Oman",
                code: "OM",
                phone: 968
            },
            {
                name: "Pakistan",
                code: "PK",
                phone: 92
            },
            {
                name: "Palau",
                code: "PW",
                phone: 680
            },
            {
                name: "Palestinian Territory, Occupied",
                code: "PS",
                phone: 970
            },
            {
                name: "Panama",
                code: "PA",
                phone: 507
            },
            {
                name: "Papua New Guinea",
                code: "PG",
                phone: 675
            },
            {
                name: "Paraguay",
                code: "PY",
                phone: 595
            },
            {
                name: "Peru",
                code: "PE",
                phone: 51
            },
            {
                name: "Philippines",
                code: "PH",
                phone: 63
            },
            {
                name: "Pitcairn",
                code: "PN",
                phone: 64
            },
            {
                name: "Poland",
                code: "PL",
                phone: 48
            },
            {
                name: "Portugal",
                code: "PT",
                phone: 351
            },
            {
                name: "Puerto Rico",
                code: "PR",
                phone: 1787
            },
            {
                name: "Qatar",
                code: "QA",
                phone: 974
            },
            {
                name: "Reunion",
                code: "RE",
                phone: 262
            },
            {
                name: "Romania",
                code: "RO",
                phone: 40
            },
            {
                name: "Russian Federation",
                code: "RU",
                phone: 7
            },
            {
                name: "Rwanda",
                code: "RW",
                phone: 250
            },
            {
                name: "Saint Barthelemy",
                code: "BL",
                phone: 590
            },
            {
                name: "Saint Helena",
                code: "SH",
                phone: 290
            },
            {
                name: "Saint Kitts and Nevis",
                code: "KN",
                phone: 1869
            },
            {
                name: "Saint Lucia",
                code: "LC",
                phone: 1758
            },
            {
                name: "Saint Martin",
                code: "MF",
                phone: 590
            },
            {
                name: "Saint Pierre and Miquelon",
                code: "PM",
                phone: 508
            },
            {
                name: "Saint Vincent and the Grenadines",
                code: "VC",
                phone: 1784
            },
            {
                name: "Samoa",
                code: "WS",
                phone: 684
            },
            {
                name: "San Marino",
                code: "SM",
                phone: 378
            },
            {
                name: "Sao Tome and Principe",
                code: "ST",
                phone: 239
            },
            {
                name: "Saudi Arabia",
                code: "SA",
                phone: 966
            },
            {
                name: "Senegal",
                code: "SN",
                phone: 221
            },
            {
                name: "Serbia",
                code: "RS",
                phone: 381
            },
            {
                name: "Serbia and Montenegro",
                code: "CS",
                phone: 381
            },
            {
                name: "Seychelles",
                code: "SC",
                phone: 248
            },
            {
                name: "Sierra Leone",
                code: "SL",
                phone: 232
            },
            {
                name: "Singapore",
                code: "SG",
                phone: 65
            },
            {
                name: "St Martin",
                code: "SX",
                phone: 721
            },
            {
                name: "Slovakia",
                code: "SK",
                phone: 421
            },
            {
                name: "Slovenia",
                code: "SI",
                phone: 386
            },
            {
                name: "Solomon Islands",
                code: "SB",
                phone: 677
            },
            {
                name: "Somalia",
                code: "SO",
                phone: 252
            },
            {
                name: "South Africa",
                code: "ZA",
                phone: 27
            },
            {
                name: "South Georgia and the South Sandwich Islands",
                code: "GS",
                phone: 500
            },
            {
                name: "South Sudan",
                code: "SS",
                phone: 211
            },
            {
                name: "Spain",
                code: "ES",
                phone: 34
            },
            {
                name: "Sri Lanka",
                code: "LK",
                phone: 94
            },
            {
                name: "Sudan",
                code: "SD",
                phone: 249
            },
            {
                name: "Suriname",
                code: "SR",
                phone: 597
            },
            {
                name: "Svalbard and Jan Mayen",
                code: "SJ",
                phone: 47
            },
            {
                name: "Swaziland",
                code: "SZ",
                phone: 268
            },
            {
                name: "Sweden",
                code: "SE",
                phone: 46
            },
            {
                name: "Switzerland",
                code: "CH",
                phone: 41
            },
            {
                name: "Syrian Arab Republic",
                code: "SY",
                phone: 963
            },
            {
                name: "Taiwan, Province of China",
                code: "TW",
                phone: 886
            },
            {
                name: "Tajikistan",
                code: "TJ",
                phone: 992
            },
            {
                name: "Tanzania, United Republic of",
                code: "TZ",
                phone: 255
            },
            {
                name: "Thailand",
                code: "TH",
                phone: 66
            },
            {
                name: "Timor-Leste",
                code: "TL",
                phone: 670
            },
            {
                name: "Togo",
                code: "TG",
                phone: 228
            },
            {
                name: "Tokelau",
                code: "TK",
                phone: 690
            },
            {
                name: "Tonga",
                code: "TO",
                phone: 676
            },
            {
                name: "Trinidad and Tobago",
                code: "TT",
                phone: 1868
            },
            {
                name: "Tunisia",
                code: "TN",
                phone: 216
            },
            {
                name: "Turkey",
                code: "TR",
                phone: 90
            },
            {
                name: "Turkmenistan",
                code: "TM",
                phone: 7370
            },
            {
                name: "Turks and Caicos Islands",
                code: "TC",
                phone: 1649
            },
            {
                name: "Tuvalu",
                code: "TV",
                phone: 688
            },
            {
                name: "Uganda",
                code: "UG",
                phone: 256
            },
            {
                name: "Ukraine",
                code: "UA",
                phone: 380
            },
            {
                name: "United Arab Emirates",
                code: "AE",
                phone: 971
            },
            {
                name: "United Kingdom",
                code: "GB",
                phone: 44
            },
            {
                name: "United States",
                code: "US",
                phone: 1
            },
            {
                name: "United States Minor Outlying Islands",
                code: "UM",
                phone: 1
            },
            {
                name: "Uruguay",
                code: "UY",
                phone: 598
            },
            {
                name: "Uzbekistan",
                code: "UZ",
                phone: 998
            },
            {
                name: "Vanuatu",
                code: "VU",
                phone: 678
            },
            {
                name: "Venezuela",
                code: "VE",
                phone: 58
            },
            {
                name: "Viet Nam",
                code: "VN",
                phone: 84
            },
            {
                name: "Virgin Islands, British",
                code: "VG",
                phone: 1284
            },
            {
                name: "Virgin Islands, U.s.",
                code: "VI",
                phone: 1340
            },
            {
                name: "Wallis and Futuna",
                code: "WF",
                phone: 681
            },
            {
                name: "Western Sahara",
                code: "EH",
                phone: 212
            },
            {
                name: "Yemen",
                code: "YE",
                phone: 967
            },
            {
                name: "Zambia",
                code: "ZM",
                phone: 260
            },
            {
                name: "Zimbabwe",
                code: "ZW",
                phone: 263
            }
        ],

        select_box = document.querySelector('.options'),
        search_box = document.querySelector('.search-box'),
        input_box = document.querySelector('input[type="tel"]'),
        selected_option = document.querySelector('.selected-option div');

    let options = null;

    for (country of countries) {
        const option = `
        <li class="option">
            <div>
                <span class="iconify" data-icon="flag:${country.code.toLowerCase()}-4x3"></span>
                <span class="country-name">${country.name}</span>
            </div>
            <strong>+${country.phone}</strong>
        </li> `;

                select_box.querySelector('ol').insertAdjacentHTML('beforeend', option);
                options = document.querySelectorAll('.option');
            }

            function selectOption() {
                console.log(this);
                const icon = this.querySelector('.iconify').cloneNode(true),
                    phone_code = this.querySelector('strong').cloneNode(true);

                selected_option.innerHTML = '';
                selected_option.append(icon, phone_code);

                input_box.value = phone_code.innerText;

                select_box.classList.remove('active');
                selected_option.classList.remove('active');

                search_box.value = '';
                select_box.querySelectorAll('.hide').forEach(el => el.classList.remove('hide'));
            }

            function searchCountry() {
                let search_query = search_box.value.toLowerCase();
                for (option of options) {
                    let is_matched = option.querySelector('.country-name').innerText.toLowerCase().includes(search_query);
                    option.classList.toggle('hide', !is_matched)
                }
            }


            selected_option.addEventListener('click', () => {
                select_box.classList.toggle('active');
                selected_option.classList.toggle('active');
            })

            options.forEach(option => option.addEventListener('click', selectOption));
            search_box.addEventListener('input', searchCountry);
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iconify/3.1.1/iconify.min.js"
    integrity="sha512-oQ1yQwSEIc9CTcK7pffEyS8fxom2iUWQrALk3j3+wfdSDjQ77EZbzGMGe2ZYmfoC8M9vR47apbS1ZYbW05y/pg=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

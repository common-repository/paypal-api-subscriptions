<div id="credit"><h2>Credit or Debit Card Information</h2><br />
<p>
		<label>First Name<br />
<input tabindex="25" type="text" id="first_name" maxlength="32" style="width: 200px;" class="input" name="first_name" value="<?php echo $_POST['first_name']; ?>"><br /><span class="small">(as it appears on card)</span>
</p>
<p>
		<label>Last Name<br />
<input tabindex="30" type="text" id="last_name" maxlength="32" style="width: 200px;" name="last_name" value="<?php echo $_POST['last_name']; ?>"><br /><span class="small">(as it appears on card)</span>
</p>
<p>
		<label>Card Type<br />
<select tabindex="35" id="credit_card_type" name="credit_card_type" ><option value=" ">Select Card</option>
<?php
$options = array(
array('value'=>'MasterCard','name'=>'MasterCard'),
array('value'=>'Visa','name'=>'Visa'),
array('value'=>'Discover','name'=>'Discover'),
array('value'=>'Amex','name'=>'American Express'));

foreach ($options as $c)
{
    $selected = '';
    if ($_POST['credit_card_type'] == $c['value'])
        $selected = 'selected="selected"';
    
    echo '<option value="'.$c['value'].'" '.$selected.'>'.$c['name'].'</option>';
}
?>
</select>
</p>
<p>
		<label>Card Number<br />

<?php 

    if (empty($_POST['cc_number']) && !empty($_POST['cc_number1']))
        $_POST['cc_number'] = $_POST['cc_number1'];
    
    if (!empty($_POST['cc_number']))
    {
        $num = str_split($_POST['cc_number']);
        
        for ($i = 0; $i < sizeof($num); $i++)
        {
            if ($i < (sizeof($num) - 4))
                $num[$i] = x;
             echo $num[$i];
        }
    }
    
?>
<input tabindex="40" type="text" id="cc_number" maxlength="19" style="width: 200px;" name="cc_number" value="">
<input type="hidden" id="cc_number1" name="cc_number1" value="<?php echo $_POST['cc_number']; ?>">
</p>
<p>
		<label>Expiration Date: </label>
<select tabindex="45" id="expdate_month" name="expdate_month">
<?php

for ($i = 1; $i < 13; $i++)
{
    $selected = '';
    if ($_POST['expdate_month'] == $i)
        $selected = 'selected="selected"';
    
    if ($i < 10)
        $m = str_pad($i,2,'0',STR_PAD_LEFT);
    else
        $m = $i;
     
    echo '<option '.$selected.'>'.$m.'</option>';
}

?></select> &nbsp;<select tabindex="50" id="expdate_year" name="expdate_year">

<?php

$options = years(20,array());

foreach ($options as $o)
{
    $selected = '';
    if ($_POST['expdate_year'] == $o)
        $selected = 'selected="selected"';
    
    echo '<option '.$selected.'>'.$o.'</option>';
}

// Recursively make a list of years to a certain value...
//
// @count - the amount of times to return
// @values - requires an array 
//
function years($count,$values)
{
    if ($count > 0)
        $count--;
    
    $date = date('Y');
    
    $values[] = $date+$count;
    if ($count)
        $values = years($count,$values);
    else
        sort($values);
        
    return $values;
}

?>
</select>
</p>
<p>
		<label>Card Security Code<br />
<input tabindex="55" type="text" id="cvv2_number" size="3" maxlength="4" name="cvv2_number" value="<?php echo $_POST['cvv2_number']; ?>">
<a target="_blank" href="https://www.paypal.com/us/cgi-bin/webscr?cmd=p/acc/cvv_info_pop-outside" ><span class="small">What's this?</span></a>
</p>
</div><div id="billing">
<h2>Billing Details</h2>


<p>
<label>Address Line 1<br />
<input tabindex="60" type="text" id="address1" size="25" maxlength="100" class="smallInputWidth" name="address1" value="<?php echo $_POST['address1']; ?>">
</label>
</p>
<p>
<label>Address Line 2<br />
<input tabindex="65" type="text" id="address2" size="25" maxlength="100" class="smallInputWidth" name="address2" value="<?php echo $_POST['address2']; ?>">
</label>
</p>
<p>
<label>City<br />
<input tabindex="70" type="text" id="city" size="25" maxlength="40" class="smallInputWidth" name="city" value="<?php echo $_POST['city']; ?>">
</label>
</p>
<p>
<label>State<br />
<input tabindex="75" type="text" id="state" size="25" maxlength="40" class="smallInputWidth" name="state" value="<?php echo $_POST['state']; ?>">
</label>
</p>

<p>
<label>Country<br />
<select tabindex="80" id="country_code" name="country_code" class="" >
<option value="">-- Choose a Country --</option>
<?php 

$options = array(
array('value'=>'US','name'=>'United States'),
array('value'=>'AL','name'=>'Albania'),
array('value'=>'DZ','name'=>'Algeria'),
array('value'=>'AD','name'=>'Andorra'),
array('value'=>'AO','name'=>'Angola'),
array('value'=>'AI','name'=>'Anguilla'),
array('value'=>'AG','name'=>'Antigua and Barbuda'),
array('value'=>'AR','name'=>'Argentina'),
array('value'=>'AM','name'=>'Armenia'),
array('value'=>'AW','name'=>'Aruba'),
array('value'=>'AU','name'=>'Australia'),
array('value'=>'AT','name'=>'Austria'),
array('value'=>'AZ','name'=>'Azerbaijan Republic'),
array('value'=>'BS','name'=>'Bahamas'),
array('value'=>'BH','name'=>'Bahrain'),
array('value'=>'BB','name'=>'Barbados'),
array('value'=>'BE','name'=>'Belgium'),
array('value'=>'BZ','name'=>'Belize'),
array('value'=>'BJ','name'=>'Benin'),
array('value'=>'BM','name'=>'Bermuda'),
array('value'=>'BT','name'=>'Bhutan'),
array('value'=>'BO','name'=>'Bolivia'),
array('value'=>'BA','name'=>'Bosnia and Herzegovina'),
array('value'=>'BW','name'=>'Botswana'),
array('value'=>'BR','name'=>'Brazil'),
array('value'=>'VG','name'=>'British Virgin Islands'),
array('value'=>'BN','name'=>'Brunei'),
array('value'=>'BG','name'=>'Bulgaria'),
array('value'=>'BF','name'=>'Burkina Faso'),
array('value'=>'BI','name'=>'Burundi'),
array('value'=>'KH','name'=>'Cambodia'),
array('value'=>'CA','name'=>'Canada'),
array('value'=>'CV','name'=>'Cape Verde'),
array('value'=>'KY','name'=>'Cayman Islands'),
array('value'=>'TD','name'=>'Chad'),
array('value'=>'CL','name'=>'Chile'),
array('value'=>'C2','name'=>'China'),
array('value'=>'CO','name'=>'Colombia'),
array('value'=>'KM','name'=>'Comoros'),
array('value'=>'CK','name'=>'Cook Islands'),
array('value'=>'CR','name'=>'Costa Rica'),
array('value'=>'HR','name'=>'Croatia'),
array('value'=>'CY','name'=>'Cyprus'),
array('value'=>'CZ','name'=>'Czech Republic'),
array('value'=>'CD','name'=>'Democratic Republic of the Congo'),
array('value'=>'DK','name'=>'Denmark'),
array('value'=>'DJ','name'=>'Djibouti'),
array('value'=>'DM','name'=>'Dominica'),
array('value'=>'DO','name'=>'Dominican Republic'),
array('value'=>'EC','name'=>'Ecuador'),
array('value'=>'SV','name'=>'El Salvador'),
array('value'=>'ER','name'=>'Eritrea'),
array('value'=>'EE','name'=>'Estonia'),
array('value'=>'ET','name'=>'Ethiopia'),
array('value'=>'FK','name'=>'Falkland Islands'),
array('value'=>'FO','name'=>'Faroe Islands'),
array('value'=>'FM','name'=>'Federated States of Micronesia'),
array('value'=>'FJ','name'=>'Fiji'),
array('value'=>'FI','name'=>'Finland'),
array('value'=>'FR','name'=>'France'),
array('value'=>'GF','name'=>'French Guiana'),
array('value'=>'PF','name'=>'French Polynesia'),
array('value'=>'GA','name'=>'Gabon Republic'),
array('value'=>'GM','name'=>'Gambia'),
array('value'=>'DE','name'=>'Germany'),
array('value'=>'GI','name'=>'Gibraltar'),
array('value'=>'GR','name'=>'Greece'),
array('value'=>'GL','name'=>'Greenland'),
array('value'=>'GD','name'=>'Grenada'),
array('value'=>'GP','name'=>'Guadeloupe'),
array('value'=>'GT','name'=>'Guatemala'),
array('value'=>'GN','name'=>'Guinea'),
array('value'=>'GW','name'=>'Guinea Bissau'),
array('value'=>'GY','name'=>'Guyana'),
array('value'=>'HN','name'=>'Honduras'),
array('value'=>'HK','name'=>'Hong Kong'),
array('value'=>'HU','name'=>'Hungary'),
array('value'=>'IS','name'=>'Iceland'),
array('value'=>'IN','name'=>'India'),
array('value'=>'ID','name'=>'Indonesia'),
array('value'=>'IE','name'=>'Ireland'),
array('value'=>'IL','name'=>'Israel'),
array('value'=>'IT','name'=>'Italy'),
array('value'=>'JM','name'=>'Jamaica'),
array('value'=>'JP','name'=>'Japan'),
array('value'=>'JO','name'=>'Jordan'),
array('value'=>'KZ','name'=>'Kazakhstan'),
array('value'=>'KE','name'=>'Kenya'),
array('value'=>'KI','name'=>'Kiribati'),
array('value'=>'KW','name'=>'Kuwait'),
array('value'=>'KG','name'=>'Kyrgyzstan'),
array('value'=>'LA','name'=>'Laos'),
array('value'=>'LV','name'=>'Latvia'),
array('value'=>'LS','name'=>'Lesotho'),
array('value'=>'LI','name'=>'Liechtenstein'),
array('value'=>'LT','name'=>'Lithuania'),
array('value'=>'LU','name'=>'Luxembourg'),
array('value'=>'MG','name'=>'Madagascar'),
array('value'=>'MW','name'=>'Malawi'),
array('value'=>'MY','name'=>'Malaysia'),
array('value'=>'MV','name'=>'Maldives'),
array('value'=>'ML','name'=>'Mali'),
array('value'=>'MT','name'=>'Malta'),
array('value'=>'MH','name'=>'Marshall Islands'),
array('value'=>'MQ','name'=>'Martinique'),
array('value'=>'MR','name'=>'Mauritania'),
array('value'=>'MU','name'=>'Mauritius'),
array('value'=>'YT','name'=>'Mayotte'),
array('value'=>'MX','name'=>'Mexico'),
array('value'=>'MN','name'=>'Mongolia'),
array('value'=>'MS','name'=>'Montserrat'),
array('value'=>'MA','name'=>'Morocco'),
array('value'=>'MZ','name'=>'Mozambique'),
array('value'=>'NA','name'=>'Namibia'),
array('value'=>'NR','name'=>'Nauru'),
array('value'=>'NP','name'=>'Nepal'),
array('value'=>'NL','name'=>'Netherlands'),
array('value'=>'AN','name'=>'Netherlands Antilles'),
array('value'=>'NC','name'=>'New Caledonia'),
array('value'=>'NZ','name'=>'New Zealand'),
array('value'=>'NI','name'=>'Nicaragua'),
array('value'=>'NE','name'=>'Niger'),
array('value'=>'NU','name'=>'Niue'),
array('value'=>'NF','name'=>'Norfolk Island'),
array('value'=>'NO','name'=>'Norway'),
array('value'=>'OM','name'=>'Oman'),
array('value'=>'PW','name'=>'Palau'),
array('value'=>'PA','name'=>'Panama'),
array('value'=>'PG','name'=>'Papua New Guinea'),
array('value'=>'PE','name'=>'Peru'),
array('value'=>'PH','name'=>'Philippines'),
array('value'=>'PN','name'=>'Pitcairn Islands'),
array('value'=>'PL','name'=>'Poland'),
array('value'=>'PT','name'=>'Portugal'),
array('value'=>'QA','name'=>'Qatar'),
array('value'=>'CG','name'=>'Republic of the Congo'),
array('value'=>'RE','name'=>'Reunion'),
array('value'=>'RO','name'=>'Romania'),
array('value'=>'RU','name'=>'Russia'),
array('value'=>'RW','name'=>'Rwanda'),
array('value'=>'VC','name'=>'Saint Vincent and the Grenadines'),
array('value'=>'WS','name'=>'Samoa'),
array('value'=>'SM','name'=>'San Marino'),
array('value'=>'ST','name'=>'Sao Tome and Principe'),
array('value'=>'SA','name'=>'Saudi Arabia'),
array('value'=>'SN','name'=>'Senegal'),
array('value'=>'SC','name'=>'Seychelles'),
array('value'=>'SL','name'=>'Sierra Leone'),
array('value'=>'SG','name'=>'Singapore'),
array('value'=>'SK','name'=>'Slovakia'),
array('value'=>'SI','name'=>'Slovenia'),
array('value'=>'SB','name'=>'Solomon Islands'),
array('value'=>'SO','name'=>'Somalia'),
array('value'=>'ZA','name'=>'South Africa'),
array('value'=>'KR','name'=>'South Korea'),
array('value'=>'ES','name'=>'Spain'),
array('value'=>'LK','name'=>'Sri Lanka'),
array('value'=>'SH','name'=>'St. Helena'),
array('value'=>'KN','name'=>'St. Kitts and Nevis'),
array('value'=>'LC','name'=>'St. Lucia'),
array('value'=>'PM','name'=>'St. Pierre and Miquelon'),
array('value'=>'SR','name'=>'Suriname'),
array('value'=>'SJ','name'=>'Svalbard and Jan Mayen Islands'),
array('value'=>'SZ','name'=>'Swaziland'),
array('value'=>'SE','name'=>'Sweden'),
array('value'=>'CH','name'=>'Switzerland'),
array('value'=>'TW','name'=>'Taiwan'),
array('value'=>'TJ','name'=>'Tajikistan'),
array('value'=>'TZ','name'=>'Tanzania'),
array('value'=>'TH','name'=>'Thailand'),
array('value'=>'TG','name'=>'Togo'),
array('value'=>'TO','name'=>'Tonga'),
array('value'=>'TT','name'=>'Trinidad and Tobago'),
array('value'=>'TN','name'=>'Tunisia'),
array('value'=>'TR','name'=>'Turkey'),
array('value'=>'TM','name'=>'Turkmenistan'),
array('value'=>'TC','name'=>'Turks and Caicos Islands'),
array('value'=>'TV','name'=>'Tuvalu'),
array('value'=>'UG','name'=>'Uganda'),
array('value'=>'UA','name'=>'Ukraine'),
array('value'=>'AE','name'=>'United Arab Emirates'),
array('value'=>'GB','name'=>'United Kingdom'),
array('value'=>'UY','name'=>'Uruguay'),
array('value'=>'VU','name'=>'Vanuatu'),
array('value'=>'VA','name'=>'Vatican City State'),
array('value'=>'VE','name'=>'Venezuela'),
array('value'=>'VN','name'=>'Vietnam'),
array('value'=>'WF','name'=>'Wallis and Futuna Islands'),
array('value'=>'YE','name'=>'Yemen'),
array('value'=>'ZM','name'=>'Zambia'));

foreach ($options as $c)
{
    $selected = '';
    if ($_POST['country_code'] == $c['value'])
        $selected = 'selected="selected"';
    
    echo '<option value="'.$c['value'].'" '.$selected.'>'.$c['name'].'</option>';
}

?>
</select>
</label>
</p>

<p>
<label>ZIP/Postal Code<br />
<input tabindex="85"  type="text" id="zip" size="10" maxlength="10" name="zip" value="<?php echo $_POST['zip']; ?>">
</label>
</p></div>
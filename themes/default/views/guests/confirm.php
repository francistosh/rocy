<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

    <title>Confirm Details!</title>
</head>
<body class="container">
<div class="d-flex justify-content-center">
    <div class=" col-md-12">
        <nav class="navbar navbar-light bg-light" style="margin-top: 10px;margin-bottom: 10px;">
           
        </nav>
        <?php $attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'confrim-form');
         echo form_open_multipart("confirms/confirm/".$guest->token); ?>
<!--        <h1>Confirm your details</h1>-->
<!--        --><?php //$attrib = array('data-toggle' => 'validator', 'role' => 'form', 'id' => 'confrim-form');
//        echo form_open_multipart("guests/confirm/".$guest->token); ?>
<!--        <h5>--><?//= lang('personal_details'); ?><!--</h5>-->
<!--        <div class="row">-->
<!---->
<!--            <div class="col-md-6">-->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("room", "room"); ?>
<!--                    <input type="text" name="data[0][room]" value="--><?php //echo $guest->room ?><!--" class="form-control" id="room" disabled required="required"/>-->
<!--                </div>-->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("full_name", "full_name"); ?>
<!--                    <input type="text" name="data[0][full_name]" value="--><?php //echo $guest->full_name ?><!--" disabled class="form-control" id="full_name" required="required"/>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("passport_id_number", "passport_id_number"); ?>
<!--                    <input type="text" name="data[0][passport_id_number]" value="--><?php //echo $guest->passport_id_number ?><!--" disabled class="form-control" id="passport_id_number" required="required"/>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("alt_email_address", "alt_email_address"); ?>
<!--                    <input type="email" name="data[0][alt_email]" value="--><?php //echo $guest->alt_email ?><!--" disabled class="form-control" id="alt_email_address"/>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("telephone", "telephone"); ?>
<!--                    <input type="tel" name="data[0][telephone]" value="--><?php //echo $guest->telephone ?><!--" disabled class="form-control" id="telephone"/>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("dob", "dob"); ?>
<!--                    <input type="date" name="data[0][dob]" value="--><?php //echo $guest->dob ?><!--" disabled class="form-control" id="dob" required="required"/>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("passport_expiry", "passport_expiry"); ?>
<!--                    <input type="date" name="data[0][passport_expiry]" value="--><?php //echo $guest->passport_expiry ?><!--" disabled class="form-control" id="passport_expiry" required="required"/>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--            <div class="col-md-6">-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("nationality", "nationality"); ?>
<!--                    <input type="text" name="data[0][nationality]" value="--><?php //echo $guest->nationality ?><!--" disabled class="form-control" id="nationality" required="required"/>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("email_address", "email_address"); ?>
<!--                    <input type="email" name="data[0][email]" value="--><?php //echo $guest->email ?><!--" disabled class="form-control" id="email_address" required="required"/>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("phone", "phone"); ?>
<!--                    <input type="tel" name="data[0][phone]" value="--><?php //echo $guest->phone ?><!--" disabled class="form-control" id="phone" required="required"/>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("alt_phone", "alt_phone"); ?>
<!--                    <input type="tel" name="data[0][alt_phone]" value="--><?php //echo $guest->alt_phone ?><!--" disabled class="form-control" id="alt_phone" />-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("gender", "gender"); ?>
<!--                    <select name="data[0][gender]" class="form-control" id="gender" required="required">-->
<!--                        --><?php //if ($guest->gender == "M") { ?>
<!--                            <option value="M" selected>Male</option>-->
<!--                        --><?php //}else if ($guest->gender == "F") { ?>
<!--                            <option value="F" selected>Female</option>-->
<!--                        --><?php //}else{ ?>
<!--                            <option value="O">Other</option>-->
<!--                        --><?php //} ?>
<!--                    </select>-->
<!--                </div>-->
<!---->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("address", "address"); ?>
<!--                    <input type="text" name="data[0][address]" value="--><?php //echo $guest->address ?><!--" disabled class="form-control" id="address" required="required"/>-->
<!--                </div>-->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("travel_history", "travel_history"); ?>
<!--                    <textarea name="data[0][travel_history]" rows="3" class="form-control" disabled>-->
<!--                            --><?php //echo $guest->travel_history ?>
<!--                        </textarea>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <h5>--><?//= lang('next_of_kin'); ?><!--</h5>-->
<!--        <div class="row">-->
<!---->
<!--            <div class="col-md-6">-->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("full_name", "full_name"); ?>
<!--                    <input type="text" name="data[1][kin_full_name]" value="--><?php //echo $next_of_kin->full_name ?><!--" disabled class="form-control" id="kin_full_name" required="required"/>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--            <div class="col-md-6">-->
<!--                <div class="form-group">-->
<!--                    --><?//= lang("phone", "phone"); ?>
<!--                    <input type="tel" name="data[1][kin_phone]" value="--><?php //echo $next_of_kin->phone ?><!--" disabled class="form-control" id="kin_phone" required="required"/>-->
<!--                </div>-->
<!--            </div>-->
<!---->
<!--        </div>-->
<!--        <h5>--><?//= lang('dietary_requirement'); ?><!--</h5>-->
<!--        --><?php //if (count($requirements)>0) { ?>
<!--            --><?php //foreach ($requirements as $requirement) { ?>
<!--                <div class="row">-->
<!--                    <div class="col-md-12">-->
<!--                        <div class="form-group">-->
<!--                            --><?//= lang("requirement", "requirement"); ?>
<!--                            <input type="text" value="--><?php //echo $requirement["requirement"] ?><!--" disabled class="form-control" required="required"/>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                </div>-->
<!--            --><?php //} ?>
<!--        --><?php //}else{ ?>
<!--            <div class="row">-->
<!--                <div class="col-md-12">-->
<!--                    <div class="form-group">-->
<!--                        --><?//= lang("requirement", "requirement"); ?>
<!--                        <input type="text" value="No specified requirements" disabled class="form-control" required="required"/>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        --><?php //} ?>
<!---->
<!--        <h5>--><?//= lang('medical_condition'); ?><!--</h5>-->
<!--        --><?php //if (count($medical_conditions)>0) { ?>
<!--            --><?php //foreach ($medical_conditions as $medical_condition) { ?>
<!--                <div class="row">-->
<!--                    <div class="col-md-12">-->
<!--                        <div class="form-group">-->
<!--                            --><?//= lang("condition", "condition"); ?>
<!--                            <input type="text" value="--><?php //echo $medical_condition["medical_condition"] ?><!--" disabled class="form-control" required="required"/>-->
<!--                        </div>-->
<!--                    </div>-->
<!---->
<!--                </div>-->
<!--            --><?php //} ?>
<!--        --><?php //}else{ ?>
<!--            <div class="row">-->
<!--                <div class="col-md-12">-->
<!--                    <div class="form-group">-->
<!--                        --><?//= lang("condition", "condition"); ?>
<!--                        <input type="text" value="No specified conditions" disabled class="form-control" required="required"/>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        --><?php //} ?>
<!---->
<!--        <h5>--><?//= lang('temperature'); ?><!--</h5>-->
<!--        --><?php //if (count($temperatures)>0) { ?>
<!--            --><?php //foreach ($temperatures as $temperature) { ?>
<!--                <div class="row">-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group">-->
<!--                            --><?//= lang("temperature", "temperature"); ?>
<!--                            <input type="text" value="--><?php //echo $temperature["temp"] ?><!--" disabled class="form-control" required="required"/>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                    <div class="col-md-6">-->
<!--                        <div class="form-group">-->
<!--                            --><?//= lang("date_time", "date_time"); ?>
<!--                            <input type="text" value="--><?php //echo $temperature["created_at"] ?><!--" disabled class="form-control" required="required"/>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            --><?php //} ?>
<!--        --><?php //}else{ ?>
<!--            <div class="row">-->
<!--                <div class="col-md-12">-->
<!--                    <div class="form-group">-->
<!--                        --><?//= lang("temperature", "temperature"); ?>
<!--                        <input type="text" value="No specified conditions" disabled class="form-control" required="required"/>-->
<!--                    </div>-->
<!--                </div>-->
<!---->
<!--            </div>-->
<!--        --><?php //} ?>
        <div class="row">
            <div class="col-md-12 text-center">
                <div id="logo-con" class="text-center">
                    <a href="https://www.tripadvisor.com/Hotel_Review-g15361711-d13202462-Reviews-Enkorok_Mara_Camp-Ololaimutiek_Maasai_Mara_National_Reserve_Rift_Valley_Province.html">
                        <img src="<?= base_url('assets/images/trip.jpg') ?>" alt="2020 Travellers Choice" width="228" height="300">
                    </a>
                </div>
                <a href="https://www.tripadvisor.com/Hotel_Review-g15361711-d13202462-Reviews-Enkorok_Mara_Camp-Ololaimutiek_Maasai_Mara_National_Reserve_Rift_Valley_Province.html">Click here to Rate us on trip advisor</a>

            </div>
        </div>
        <h4>Guest feedback form<br>
            Getting things right for you is an important part of what we do –and we really would like to
            hear your feedback about your stay with us.</h4>
        <h5>Rating</h5>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    Rating
                    <input type="number" name="rating" value="10"  class="form-control" required="required"/>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>Briefing on arrival</p>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                   <select class="form-control" name="briefing">
                       <option value="excellent">Excellent</option>
                       <option value="good">Good</option>
                       <option value="fair">Fair</option>
                       <option value="poor">Poor</option>
                   </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>How did you find the Camp
                    Ambience?</p>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select class="form-control" name="ambience">
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>Tents amenities</p>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select class="form-control" name="tent_amenities">
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>Tents cleanliness</p>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select class="form-control" name="tent_cleanliness">
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>Bar and Restaurant Ambience</p>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select class="form-control" name="bar_restaurant_ambience">
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>How did you find the food quality?</p>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select class="form-control" name="food_quality">
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>Restaurant staff service</p>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select class="form-control" name="staff_service">
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>Did you feel welcome by the
                    Enkorok mara camp staff?</p>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select class="form-control" name="felt_welcomed">
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>Camps COVID 19 SOPs compliance
                    Overall rating</p>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select class="form-control" name="covid_compliance">
                        <option value="excellent">Excellent</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>
                        <option value="poor">Poor</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <p>Would you stay with us again or recommend the camp to other travelers?</p>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <select class="form-control" name="stay_again">
                        <option value="yes">Yes</option>
                        <option value="no">No</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    Any other comments
                    <textarea name="comment"  class="form-control" required="required"  rows="3">

                    </textarea>
                </div>
            </div>
        </div>
        <div class="form-group">
            <?php echo form_submit('confirm_guest', lang('Submit Details'), 'class="btn btn-primary"'); ?>
        </div>
        <?php echo form_close(); ?>
    </div>

</div>
<footer class="text-muted">
    <div class="container">
        <p class="float-right">
            <a href="#">Back to top</a>
        </p>
        <p> © 2020 | Enkorok Mara Camp LTD.</p>
        <p><a href="https://www.enkorokmaracamp.com/wp-content/uploads/2020/07/Enkorok-Covid-19-Certification.pdf" target="_blank" rel="noopener noreferrer">THE STANDARD IMPLEMENTED COVID-19 SAFETY PROTOCOL</a></p>
    </div>
</footer>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
</body>
</html>



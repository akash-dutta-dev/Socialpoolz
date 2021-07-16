
<div class="uk-position-relative uk-visible-toggle uk-light" uk-slider>

    <ul class="uk-slider-items uk-child-width-1-2 uk-child-width-1-3@s uk-child-width-1-4@m">
        <li>
            <img src="assets/images/profile_pics/head_alizarin.png" alt="">
            <div class="uk-position-center uk-panel"></div>
        </li>
        <li>
            <img src="assets/images/profile_pics/head_amethyst.png" alt="">
            <div class="uk-position-center uk-panel"></div>
        </li>
        <li>
            <img src="assets/images/profile_pics/head_belize_hole.png" alt="">
            <div class="uk-position-center uk-panel"></div>
        </li>
        <li>
            <img src="assets/images/profile_pics/head_carrot.png"  alt="">
            <div class="uk-position-center uk-panel"></div>
        </li>
       <li>
            <img src="assets/images/profile_pics/head_alizarin.png" alt="">
            <div class="uk-position-center uk-panel"></div>
        </li>
        <li>
            <img src="assets/images/profile_pics/head_amethyst.png" alt="">
            <div class="uk-position-center uk-panel"></div>
        </li>
        <li>
            <img src="assets/images/profile_pics/head_belize_hole.png" alt="">
            <div class="uk-position-center uk-panel"></div>
        </li>
        <li>
            <img src="assets/images/profile_pics/head_carrot.png"  alt="">
            <div class="uk-position-center uk-panel"></div>
        </li><li>
            <img src="assets/images/profile_pics/head_alizarin.png" alt="">
            <div class="uk-position-center uk-panel"></div>
        </li>
        <li>
            <img src="assets/images/profile_pics/head_amethyst.png" alt="">
            <div class="uk-position-center uk-panel"></div>
        </li>
        <li>
            <img src="assets/images/profile_pics/head_belize_hole.png" alt="">
            <div class="uk-position-center uk-panel"></div>
        </li>
        <li>
            <img src="assets/images/profile_pics/head_carrot.png"  alt="">
            <div class="uk-position-center uk-panel"></div>
        </li>
    </ul>

    <a class="uk-position-center-left uk-position-small" href="#" uk-slidenav-previous uk-slider-item="previous"></a>
    <a class="uk-position-center-right uk-position-small" href="#" uk-slidenav-next uk-slider-item="next"></a>

</div>

$data_query = mysqli_query($this->con, "SELECT * FROM answer_later WHERE user_added='$this->user_from' ORDER BY id DESC");

while($row = mysqli_fetch_array($data_query)) {
					$question_id = $row['question_id'];
					$data_query_question = mysqli_query($this->con, "SELECT * FROM question WHERE id='$question_id'");
					$row_question = mysqli_fetch_array($data_query_question);

					$id = $row_question['id'];
					$question_body = $row_question['question_body'];
					$question_link = $row_question['question_link'];
					$posted_by = $row_question['posted_by'];
					$date_time = $row_question['date_added'];
					$thisUser = $this->user_from;

$str = "<h5 style='text-align: centre;'>No question Added.<br>Add some Questions To Answer Later</h5>";

 uk-toggle='target: #edit_answer$id' 
<?php flash(promptMessage('message')); ?>

<div class="content-wrapper">
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row justify-content-between">
            <div class="mb-12 col-lg-12 col-xl-12 col-12 mb-0">
                <h4 class="lh-lg mb-0 fw-bolder">SOA <span class="text-primary">[ <?=$data['letter']['name']?> ]</span></h4>
                <h6>Please review all details before making any changes, as any changes made will affect the automation of sending.</h6>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="post" id="soaLetterForm">
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <div class="mb-3">
                                        <label for="start-schedule" class="form-label">Start of Sending Schedule</label>
                                        <input type="text" name="schedule" id="start-schedule" value="<?= $data['letter']['schedule']?>" id="start-schedule" class="form-control flatpickr-datetime" placeholder="" />
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="frequency" class="form-label">Frequency</label>
                                    <select class="form-select select2" name="frequency" id="frequency">
                                        <option value="dailyOn" <?=$data['letter']['frequency'] == "dailyOn" ? "selected" : ""?>>Daily at <?=date("g:i A", strtotime($data['letter']['schedule']))?></option>
                                        <option value="monthlyOn" <?=$data['letter']['frequency'] == "monthlyOn" ? "selected" : ""?>><?=$data['letter']['frequency_label']?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-6 col-md-6 col-sm-12">
                                    <label for="category" class="form-label">Categories</label>
                                    <div class="select2-primary">
                                        <select id="category" name="categories[]" class="select2 form-select" multiple>
                                            <option value="Agent">Agent</option>
                                            <option value="Broker">Broker</option>
                                            <option value="Direct">Direct</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-6 col-xs-12 mb-5">
                                    <label for="is_active" class="form-label">Status</label>
                                    <select id="is_active" name="is_active" class="select2 form-select">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                                <input type="hidden" value="<?=getVar('id')?>" name="id">
                            </div>
                            <?php
                                if($data['letter']['content'] != ""){
                            ?> 
                                    <div class="row mb-5">
                                        <div class="col-lg-12 col-md-12 col-sm-12">
                                            <div id="full-editor">
                                                <?=$data['letter']['content']?>
                                            </div>
                                        </div>
                                    </div>
                            <?php
                                }
                            ?>
                            <div class="row justify-content-end">
                                <div class="col-lg-12 col-md-12 col-xs-12 mb-5 text-end">
                                    <a href="/finance/soa-letter" class="btn btn-label-secondary"><i class="icon-base ti tabler-arrow-back me-1"></i>Back</a>
                                    <a data-bs-toggle="modal" data-bs-target="#downloadLetterModal" data-id="<?= $data['letter']['name'] ?>" class="btn btn-label-primary"><i class="icon-base ti tabler-download me-1"></i>Download</a>
                                    <button name="save" id="save" class="btn btn-primary"><i class="icon-base ti tabler-device-floppy me-1"></i>Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="downloadLetterModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form method="post" id="teamLeaderForm">
            <div class="modal-content">
                <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel3">Download</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-2">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <select id="as_of_date" name="as_of_date" class="select2 form-select">
                                <option value="">Period</option>
                                <?php
                                    $today = new DateTime();
                                    for ($i = 0; $i < 3; $i++) {
                                        $month = (clone $today)->modify("-$i month");
                                        $lastDay = (clone $month)->modify('last day of this month');

                                        $value = $lastDay->format("Y-m-d");
                                        $label = $month->format("F Y");

                                        echo "<option value='$value'>$label</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <select id="master_list_id" name="master_list_id" placeholder="Period" class="select2 form-select">
                                <?=tool_dropdown_option($data['source_name'], '', 'source_name')?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" value="" name="name">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary download">Download</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    var flatpickrDateTime = document.querySelectorAll(".flatpickr-datetime");
    var content = <?=json_encode($data['letter']['content'])?>;
    let fullEditor;

    flatpickrDateTime.flatpickr({
        enableTime: true,
        dateFormat: "Y-m-d H:i:S",   // value sent to backend (hidden real value)
        altInput: true,
    });

    if(content != null){
        fullEditor = new Quill('#full-editor', {
            bounds: '#full-editor',
            placeholder: 'Type Something...',
            modules: {
                formula: true,
                toolbar: [
                            ['bold', 'italic', 'underline'],     // text formatting
                            ['direction', { align: [] }],        // text direction + alignment
                            [{ list: 'ordered' }, { list: 'bullet' }] // lists
                        ]
            },
            theme: 'snow'
        });
    }
    

    function formatDayWithSuffix(day) {
        if (day > 3 && day < 21) return day + "th"; // 11th–20th
        switch (day % 10) {
            case 1:  return day + "st";
            case 2:  return day + "nd";
            case 3:  return day + "rd";
            default: return day + "th";
        }
    }

    var category = <?=json_encode($data['letter']['categories'])?>;
    var arr = JSON.parse(category); 

    $('#category').val(arr).trigger('change');
    $('#is_active').val(<?=json_encode($data['letter']['is_active'])?>)

    $('#start-schedule').on('change', function(){
        var startSched = $(this).val();
        var dateObj = new Date(startSched);
        var dayNumber = dateObj.getDate(); 
        var dayFormatted = formatDayWithSuffix(dayNumber);

        var hours = dateObj.getHours();
        var minutes = dateObj.getMinutes();
        var ampm = hours >= 12 ? "PM" : "AM";
        hours = hours % 12;
        hours = hours ? hours : 12;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        var formattedTime = hours + ":" + minutes + " " + ampm;

        $('#frequency').find('[value="monthlyOn"]').text('Every '+dayFormatted+' of the month at '+formattedTime);
        $('#frequency').find('[value="dailyOn"]').text('Daily at '+formattedTime);
        $('#frequency').select2('destroy').select2();
    });

    $('#save').click(function(e){
        e.preventDefault();
        
        var formData = new FormData(document.getElementById("soaLetterForm")); 
        formData.append('frequency_label', $('#frequency option:selected').text().trim());

        if(content != null){
            formData.append('content', fullEditor.root.innerHTML);
        }

        $.ajax({
            url: '/finance/soaLetter_json/',
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(data){
                console.log(data);
            }
        });

    });

    $('.download').click(function(){
        $.ajax({
            url: '/finance/soaLetterDownload_json/',
            method: 'POST',
            data: {
                id              : <?=getVar('id')?>,
                master_list_id : $('[name="master_list_id"]').val()
            },
            success: function(data){
                
            }
        });
    });
</script>
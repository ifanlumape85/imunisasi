<script type="text/javascript">

var save_method; //for save method string
var table;
var base_url = '<?php echo base_url();?>';
var user_level_id = "<?php echo $this->session->userdata('user_level_id'); ?>";

$(document).ready(function() {

    //datatables
    table = $('#table').DataTable({ 
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,
        "autoWidth": true,
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "scrollX": true,
        "order": [], //Initial no order.

        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('profile/ajax_list')?>",
            "type": "POST"
        },

        //Set column definition initialisation properties.
        "columnDefs": [
        { 
            "targets": [ -1 ], //last column
            "orderable": false, //set not orderable
        },
        ],

    });

    //datepicker
    $('.datepicker').datepicker({
        autoclose: true,
        format: "yyyy-mm-dd",
        todayHighlight: true,
        orientation: "top auto",
        todayBtn: true,
        todayHighlight: true,  
    });

    //set input/textarea/select event when change value, remove class error and remove text help block 
    $("input").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("textarea").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });
    $("select").change(function(){
        $(this).parent().parent().removeClass('has-error');
        $(this).next().empty();
    });

});

function add_user()
{
    save_method = 'add';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string
    $('input[name=user_aktif][value=0]').prop('checked', 'checked');
    $('#modal_form').modal('show'); // show bootstrap modal
    $('.modal-title').text('Add User'); // Set Title to Bootstrap modal title

    $('#photo-preview').hide(); // hide photo preview modal

    $('#label-photo').text('Upload Photo'); // label photo upload
}

function edit_user(id)
{
    save_method = 'update';
    $('#form')[0].reset(); // reset form on modals
    $('.form-group').removeClass('has-error'); // clear error class
    $('.help-block').empty(); // clear error string

    //Ajax Load data from ajax
    $.ajax({
        url : "<?php echo site_url('profile/ajax_edit/')?>/" + id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {

            $('[name="id"]').val(data.user_id);
            $('[name="user_full_name"]').val(data.user_full_name);
            $('[name="user_email"]').val(data.user_email);
            $('[name="user_name"]').val(data.user_name);
            $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Edit User'); // Set title to Bootstrap modal title
            
            
            $('#photo-preview').show(); // show photo preview modal
			
            if(data.user_photo)
            {
                $('#label-photo').text('Change Photo'); // label photo upload
                $('#photo-preview div').html('<img src="'+base_url+'upload/user/thumbs/'+data.user_photo+'" class="img-responsive">'); // show photo
                $('#photo-preview div').append('<input type="checkbox" name="remove_photo" value="'+data.user_photo+'"/> Remove photo when saving'); // remove photo

            }
            else
            {
                $('#label-photo').text('Upload Photo'); // label photo upload
                $('#photo-preview div').text('(No photo)');
            }            
            
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}

function reload_user_table()
{
    table.ajax.reload(null,false); //reload datatable ajax 
}

function save()
{
    $('#btnSave').text('saving...'); //change button text
    $('#btnSave').attr('disabled',true); //set button disable 
    var url;

    if(save_method == 'add') {
        url = "<?php echo site_url('profile/ajax_add')?>";
    } else {
        url = "<?php echo site_url('profile/ajax_update')?>";
    }

    // ajax adding data to database
    var formData = new FormData($('#form')[0]);
    $.ajax({
    	url : url,
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "JSON",
        success: function(data)
        {

            if(data.status) //if success close modal and reload ajax table
            {
                reload_user_table();
                if (save_method== 'add')
                {
				    $('#form')[0].reset(); // reset form on modals
                    $('.form-group').removeClass('has-error'); // clear error class
				    $('.help-block').empty(); // clear error string
				    $('.modal-title').text('Add User'); // Set Title to Bootstrap modal title 
				}
				else
				{
					$('#modal_form').modal('hide');
				}                
            }
            else
            {
                for (var i = 0; i < data.inputerror.length; i++) 
                {
                    $('[name="'+data.inputerror[i]+'"]').parent().parent().addClass('has-error'); //select parent twice to select div form-group class and add has-error class
                    $('[name="'+data.inputerror[i]+'"]').next().text(data.error_string[i]); //select span help-block class set text error string
                }
            }
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 


        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error adding / update data');
            $('#btnSave').text('save'); //change button text
            $('#btnSave').attr('disabled',false); //set button enable 

        }
    });
}

function delete_user(id)
{
    if(confirm('Are you sure delete this data?'))
    {
        // ajax delete data to database
        $.ajax({
            url : "<?php echo site_url('profile/ajax_delete')?>/"+id,
            type: "POST",
            dataType: "JSON",
            success: function(data)
            {
                //if success reload ajax table
                $('#modal_form').modal('hide');
                reload_user_table();
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                alert('Error deleting data');
            }
        });

    }
}
</script>

<!-- Bootstrap modal -->
<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">User Form</h3>
            </div>
            <div class="modal-body form">
                <form action="#" id="form" class="form-horizontal">
                    <input type="hidden" value="" name="id"/> 
                    <div class="form-body">
		
					<div class="form-group">
						<label class="control-label col-md-3" for="user_full_name">Nama Lengkap</label>
						<div class="col-md-9">
	    				<input placeholder="Name " type="text" name="user_full_name" class="form-control" />
						<span class="help-block"></span>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-3" for="user_email">Email</label>
						<div class="col-md-9">
	    				<input placeholder="Email" type="text" name="user_email" class="form-control" />
						<span class="help-block"></span>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-3" for="user_name">Username</label>
						<div class="col-md-9">
	    				<input placeholder="Name" type="text" name="user_name" class="form-control" />
						<span class="help-block"></span>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-3" for="user_password">Password</label>
						<div class="col-md-9">
	    				<input placeholder="Password" type="password" name="user_password" class="form-control" />
						<span class="help-block"></span>
						</div>
					</div>
					
	
               <div class="form-group" id="photo-preview">
                    <label class="control-label col-md-3">Photo</label>
                    <div class="col-md-9">
                        (No photo)
                        <span class="help-block"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label col-md-3" id="label-photo">Upload Photo</label>
                    <div class="col-md-9">
                        <input name="photo" type="file">
                        <span class="help-block"></span>
                     </div>
                </div>
					
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" id="btnSave" onclick="save()" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->
		
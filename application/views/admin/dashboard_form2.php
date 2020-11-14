<script type="text/javascript">

var save_method; //for save method string
var table;
var base_url = '<?php echo base_url();?>';
var id_instansi = '<?php echo $id_instansi; ?>';
var user_level_id = '<?php echo $this->session->userdata('user_level_id'); ?>';
var tgl_presensi = '<?php echo date('Y-m-d'); ?>';

$(document).ready(function() {

    $('.breadcrumb').html('<li><a href="'+base_url+'"><i class="fa fa-dashboard"></i> Beranda</a></li><li><a href="'+base_url+'">Dashboard</a></li><li class="active">List</li>');

    setInterval(function(){
        dashboard();    
    }, 2000);
});

function dashboard()
{
    var url = "<?php echo site_url('jenis_presensi/data_presensi/')?>";
    $.ajax({
        type: "POST",
        data: {id_instansi:id_instansi, tgl_presensi: tgl_presensi},
        url: url,
        dataType: "text",
        success: function(data)
        {
            $('.dashboardx').html(data);
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error : pengambilan data');
        }
    });
}

function jenis_presensi(id)
{
	 $.ajax({
        url : "<?php echo site_url('presensi/pegawai/')?>/",
        type: "POST",
        data: {id_instansi:id_instansi, tgl_presensi: tgl_presensi, id_jenis_presensi:id},
        dataType: "text",
        success: function(data)
        {
        	$('.form').html(data);
			$('#modal_form').modal('show'); // show bootstrap modal when complete loaded
            $('.modal-title').text('Daftar Pegawai'); // Set title to Bootstrap modal title           
        },
        error: function (jqXHR, textStatus, errorThrown)
        {
            alert('Error get data from ajax');
        }
    });
}
</script>

<div class="modal fade" id="modal_form" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title">ABSENSI</h3>
            </div>
            <div class="modal-body form">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-flat btn-sm" data-dismiss="modal"><i class="fa fa-undo"></i> Tutup</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- End Bootstrap modal -->	
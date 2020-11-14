<script type="text/javascript">

var save_method; //for save method string
var table;
var base_url = '<?php echo base_url();?>';
var user_level_id = '<?php echo $this->session->userdata('user_level_id'); ?>';

$(document).ready(function() {

    $('.breadcrumb').html('<li><a href="'+base_url+'"><i class="fa fa-dashboard"></i> Beranda</a></li><li><a href="'+base_url+'">Dashboard</a></li><li class="active">List</li>');

    setInterval(function(){
     
    }, 2000);
});



</script>
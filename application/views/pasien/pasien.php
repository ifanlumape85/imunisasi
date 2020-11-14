    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><? echo isset($title) ? ucwords(str_replace('_', ' ', $title)) : ''; ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
        		<button class="btn btn-success btn-flat btn-sm" onclick="add_pasien()"><i class="glyphicon glyphicon-plus"></i> Tambah</button>
        		<button class="btn btn-default btn-flat btn-sm" onclick="reload_pasien_table()"><i class="glyphicon glyphicon-refresh"></i> Muat Ulang</button>
        		<button class="btn btn-danger btn-flat btn-sm" onclick="bulk_delete()"><i class="glyphicon glyphicon-trash"></i> Hapus Masal</button>
  


		<div class="col-sm-12 table-responsive"><br />
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
		 		<tr>
		 			<th><input type="checkbox" id="check-all"></th>
		            <th>No.</th>
					<th>Nama</th>
					<th>Tempat/Tgl Lahir</th>
					<th>No. Telp</th>
          <th>Photo</th>
					<th>Tgl Input</th>
					<th>Tgl Update</th>
          <th style="width:125px;">Action</th>
				</tr>                
            </thead>
            <tbody>
            </tbody>

            <tfoot>

		 		<tr>
		 			<td></td>
		 			<td></td>
					<td>Nama </td>
					<td>Tempat/Tgl Lahir</td>
					<td>No. Telp</td>
          <td>Photo</td>
					<td>Tgl Input</td>
					<td>Tgl Update</td>
          <td></td>
				</tr>
           </tfoot>
       </table>
       </div>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>    
      <!-- /.row -->
    </section>   
	
		
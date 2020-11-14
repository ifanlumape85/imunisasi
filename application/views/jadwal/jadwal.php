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
        		<button class="btn btn-success btn-flat btn-sm" onclick="add_jadwal()"><i class="glyphicon glyphicon-plus"></i> Tambah</button>
        		<button class="btn btn-default btn-flat btn-sm" onclick="reload_jadwal_table()"><i class="glyphicon glyphicon-refresh"></i> Muat Ulang</button>
        		<button class="btn btn-danger btn-flat btn-sm" onclick="bulk_delete()"><i class="glyphicon glyphicon-trash"></i> Hapus Masal</button>
        <br />
        <br />

        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title" >Custom Filter : </h3>
            </div>
            <div class="panel-body">
                <form id="form-filter" class="form-horizontal">
		
		          <div class="form-group">
                        <label for="user" class="col-sm-2 control-label">User </label>
                        <div class="col-sm-4">
                            <?php echo $form_user2; ?>
                        </div>
                    </div>
				
		            <div class="form-group">
                        <label for="btn-filter" class="col-sm-2 control-label"></label>
                        <div class="col-sm-4">
                            <button type="button" id="btn-filter" class="btn btn-primary btn-flat btn-sm"><i class="fa fa-filter"></i> Filter</button>
                            <button type="button" id="btn-reset" class="btn btn-default btn-flat btn-sm"><i class="fa fa-undo"></i> Reset</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
		<div class="col-sm-12 table-responsive">
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
		 		<tr>
		 			<th><input type="checkbox" id="check-all"></th>
		            <th>No.</th>
					<th>User</th>
					<th>Jadwal</th>
					<th>File</th>
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
					<td>User </td>
					<td>Jadwal</td>
					<td>File</td>
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
	
		
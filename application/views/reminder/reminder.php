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
            <button class="btn btn-default btn-flat btn-sm" onclick="reload_reminder_table()"><i class="glyphicon glyphicon-refresh"></i> Muat Ulang</button>
            <button class="btn btn-danger btn-flat btn-sm" onclick="bulk_delete()"><i class="glyphicon glyphicon-trash"></i> Hapus Masal</button>
        <br />
        <br />


    <div class="col-sm-12 table-responsive">
        <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
        <tr>
          <th><input type="checkbox" id="check-all"></th>
                <th>No.</th>
          <th>Judul</th>
          <th>Isi</th>
          <th>Tgl</th>
          <th style="width:125px;">Action</th>
        </tr>                
            </thead>
            <tbody>
            </tbody>

            <tfoot>

        <tr>
          <td></td>
          <td></td>
          <td>Judul </td>
          <td>Isi</td>
          <td>Tgl</td>
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
  
    
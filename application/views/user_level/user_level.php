
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List <? echo isset($title) ? ucwords(str_replace('_', ' ', $title)) : ''; ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <button class="btn btn-success btn-sm" onclick="add_user_level()"><i class="glyphicon glyphicon-plus"></i> Tambah</button>
                <button class="btn btn-default btn-sm" onclick="reload_user_level_table()"><i class="glyphicon glyphicon-refresh"></i> Muat Ulang</button>
                <br />
                <br />
                <table id="table" class="table table-bordered table-hover">
                    <thead>
                        <tr>		
        		          <th>Level</th>
                          <th>Description</th>
        	               <th style="width:125px;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>

                    <tfoot>
                    <tr>
        	           <th>Level</th>
                       <th>Description</th>
        	           <th>Action</th>
                   </tr>
                   </tfoot>
               </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>	
      <!-- /.row -->
    </section>      
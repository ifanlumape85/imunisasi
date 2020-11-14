    <section class="content">
      <div class="row">
        <div class="col-xs-12">
          <!-- /.box -->

          <div class="box">
            <div class="box-header">
              <h3 class="box-title">List <? echo isset($title) ? ucwords(str_replace('_', ' ', $title)) : ''; ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
            <table id="table" class="table table-striped table-bordered" cellspacing="0" width="100%">
		            <thead>
		                <tr>		
		                  <th>Nama Lengkap</th>
                      <th>Email</th>
                      <th>Username</th>
                      <th>Photo</th>
				              <th style="width:125px;">Action</th>
                </tr>
            </thead>
            <tbody>
            </tbody>

            		<tfoot>
            		<tr>
                  <th>Nama Lengkap</th>
                  <th>Email</th>
                  <th>Username</th>
                  <th>Photo</th>
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
	
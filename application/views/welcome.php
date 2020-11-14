  <!-- SECTION -->
  <div class="section">
    <!-- container -->
    <div class="container">
      <!-- row -->
      <div class="row">
        <div class="col-md-8">
          <!-- row -->
          <div class="row">
            <div class="col-md-12" id="telusuri_ph">
              <div class="section-title">
                <h2 class="title">Telusuri Produk Hukum Kabupaten Bolaang Mongondow</h2>
              </div>
            </div>

            <!-- post -->
            <div class="col-md-12">
              <?php $this->load->view('form_pencarian'); ?>
            </div>
            <!-- /post -->

            <div class="clearfix visible-md visible-lg"></div>
            <!-- row -->
            <?php $this->load->view('produk_hukum_terbaru'); ?>
            <!-- /row -->
          </div>
          <!-- /row -->
        </div>

        <div class="col-md-4">
        <?php $this->load->view('berita_terkini'); ?>
        </div>
      </div>
      <!-- /row -->
    </div>
    <!-- /container -->
  </div>
  <!-- /SECTION -->
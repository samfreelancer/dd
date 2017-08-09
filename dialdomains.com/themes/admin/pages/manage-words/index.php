
<?php include $this->tpl->header(); ?>

        <h2>Words</h2>

        <div class="content-box">
          <div class="content-box-header">
            <!-- <h3>Content box</h3> -->
            <ul class="content-box-tabs">
              <li><a href="#tab1" class="default-tab">Words</a></li>
            </ul>

            <div class="clear"></div>
          </div>

          <div class="content-box-content">
            <div class="tab-content default-tab" id="tab1">
                <p>There are currently <?php echo number_format($wordCount); ?> words in the database.</p>
            </div>
          </div>
        </div>

<?php include $this->tpl->footer(); ?>


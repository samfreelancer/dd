
<?php include $this->tpl->header(); ?>

        <h2>Import Word List</h2>

        <div class="content-box">
          <div class="content-box-header">
            <!-- <h3>Content box</h3> -->
            <ul class="content-box-tabs">
              <li><a href="#tab1" class="default-tab">Word Import</a></li>
            </ul>

            <div class="clear"></div>
          </div>

          <div class="content-box-content">
            <div class="tab-content default-tab" id="tab1">
              <form class="form label-inline" name="wordImport" id="wordImport" action="" method="post" enctype='multipart/form-data'>
                <p>
                  <label for="wordList">Word List</label>
                  <input type="file" name="wordList" />
                </p>
                <p>
                  <input class="button" type="submit" value="Submit" />
                </p>
              </form>
            </div>
          </div>
        </div>

<?php include $this->tpl->footer(); ?>


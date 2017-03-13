<html>
   <body>
      <div> Friday!!</div>

      <?php
         echo Form::open(array('url' => '/upload','files'=>'true'));
         echo 'Select the file to upload.';
         echo Form::file('build');
         echo Form::submit('Upload File');
         echo Form::close();
      ?>
   
   </body>
</html>
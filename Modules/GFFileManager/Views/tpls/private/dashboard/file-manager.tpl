 {include file="../../head.tpl"}
 <link rel="stylesheet" href="modules/GFFileManager/css/styles-filemanager.css">
      <main class="mdl-layout__content mdl-color--grey-100">
        <div class="mdl-grid">
          <div class="demo-cards mdl-cell mdl-cell--12-col mdl-cell--12-col-tablet mdl-grid mdl-grid--no-spacing">
             	<div class="filemanager">
			
					<div class="search">
					<i class="material-icons">search</i>
						<input type="search" placeholder="Find a file.." />
					</div>
			
					<div class="breadcrumbs"></div>
			
					<ul class="data"></ul>
			
					<div class="nothingfound">
						<div class="nofiles"></div>
						<span>No files here.</span>
					</div>
			
				</div>
         
            
          </div>
        </div>
      </main>
  {include file="../../foot.tpl"}
  <script type="text/javascript">
  var filesData = {$fileResult};
  console.log(filesData)
</script>
  	<script src="modules/GFFileManager/js/filemanager.js"></script>
  	
</html>
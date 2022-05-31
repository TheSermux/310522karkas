<?php 
	use RedCore\Infodocs\Collection as Infodocs;
	use RedCore\Request as Request;
	use RedCore\Forms as Forms;
	use RedCore\Session as Session;


	$html_object = "oinfodocsobjects";
	
	
	
	$lb_params = array(
        "id" => Request::vars("oinfodocsobjects_id"),
    );
	
	Infodocs::setObject($html_object);
	$item = Infodocs::loadBy($lb_params);
	$item = $item->object;
	

	$form = Forms::Create()
		->add("action",   "action",   "hidden", "action",                     $html_object. ".store.do",         6, false)
		->add("redirect", "redirect", "hidden", "redirect",                   "infodocs-objects",                       6, false)
		->add("id",          "Id",       "hidden", $html_object . "[id]",        htmlspecialchars($item->id),        6, false)
		->add("name",         "Имя",      "text",   $html_object . "[name]", htmlspecialchars($item->name), 6, true)	
		->parse();	
?>


<div class="row">
  <div class="col-md-12 col-sm-12 ">
    <div class="x_panel">
      <div class="x_title">
        <h2>НОРМАТИВНО-СПРАВОЧНАЯ ДОКУМЕНТАЦИЯ<small>редактирование справочника объектов></h2>
        
        <div class="clearfix"></div>
      </div>
      <div class="x_content">
          <div class="row">
          <div class="col-sm-12">
            <div class="card-box table-responsive">
                <?=$form?>
       		</div>
	  	  </div>
  		</div>
</div>
    </div>
  </div>
</div>


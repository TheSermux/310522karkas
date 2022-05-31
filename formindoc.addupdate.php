<?php

use RedCore\Forms;
use RedCore\Request;
use RedCore\Where;
use RedCore\Indoc\Collection as Indoc;
use RedCore\Session;
use RedCore\Users\Collection as Users;
use \RedCore\Infodocs\Collection as Agents;
use \RedCore\Infodocs\Collection as Works;

//require_once ('requisites.php');

$html_object = "oindoc";

Indoc::setObject($html_object);

$lb_params = array(
    "id" => Request::vars("oindoc_id")
);

$doc_type = Request::vars("type");
if (is_null($doc_type)) {
    $doc_type = $oindoc_item->object->params->doctypes;
}

$oindoc_item = Indoc::loadBy($lb_params);

$select_params["list"] = Indoc::getStatuslist();

$where = Where::Cond()
    ->add("_deleted", "=", "0")
    ->parse();

Indoc::setObject("odoctypes");

$DocTypes = Indoc::getList($where);

$DocTypesid = array();
foreach ($DocTypes as $id => $temp) {
    $DocTypesid[$id] = $temp->object->id;
}

$DocTypesAcceess = Users::GetDocTypesByUser($DocTypesid);

$DocTypesResult["list"][0] = "Не выбрано";
foreach ($DocTypesAcceess as $key => $item) {
    if ($item) {
        $DocTypesResult["list"][$key] = $DocTypes[$key]->object->title;
    }
}

Users::setObject("user");

$user_role = Users::getAuthRole();

$user_id = $oindoc_item->object->user_id;
if (is_null($user_id))$user_id = Users::getAuthId();


$step = $oindoc_item->object->step;

$step_role = $oindoc_item->object->step_role;

$status_id = $oindoc_item->object->params->status_id;
if (is_null($status_id)) $status_id = "1";

// if (is_null(Request::vars("oindoc_id"))) {
//     $step = "1";
//     $step_role = $user_role;
// }

//  disabling form by role
$disable_form = "disabled";
$hidden_date = 'hidden';

if( $user_role == "2" || $user_role == "1"|| $user_role == "19") {
	$disable_form = "";
    $hidden_date = 'date';
}


if (is_null($doc_type)) {
    $doc_type = $oindoc_item->object->params->doctypes;
}
Session::set("s_relateddoc_id", $oindoc_item->object->id);
$relateddocs = require('RelatedDocView/generateRelatedDocView.php');
Session::delete("s_relateddoc_id", $oindoc_item->object->id);

$auto_reg_number = Indoc::getRegNumber();
$reg_number = $oindoc_item->object->reg_number;
if (is_null($reg_number)) {

	$reg_number = $auto_reg_number;
}

$regDate = $oindoc_item->object->reg_date;
$regDate1 = date("Y-m-d", strtotime($regDate));
if (is_null($oindoc_item->object->reg_date)) {
    $oindoc_item->object->reg_date = date('Y-m-d');
	$regDate1 = $oindoc_item->object->reg_date;
	//print_r($regDate1);
}

Indoc::setObject('odocfile');
$lb_params = array(
  'doc_id' => $doc_id,
  'iscurrent' => '1'
);
$doc_file = Indoc::loadBy($lb_params);
if (!empty($doc_file->object->id)){
    $downloadBtn = '<div class="row form-group" style="margin-top: 15px;"><div class="col col-md-12"><label for="" class="form-control-label">Прикрепленный файл</label></div>
                        <div class="col col-md-12">
                            <div class="row form-control">
                                <div class="col col-md-4">
                                    <div style="font-size: 17px; font-weight:600" >'.$doc_file->object->name.' от '.date('d.m.Y', strtotime($doc_file->object->_updated)).'</div>
                                </div>
                                <div class="col col-md-8">
                                    <a class="btn btn-info btn-sm" href = "/docs-download?file_id='.$doc_file->object->id .'">Скачать документ</a>
                                </div>
                            </div>
                        </div>
                    </div>';
}

/*
//реквизиты -начало
*/
$obj_requisites = "orequisites";

Indoc::setObject('orequisites');
$lb_params = array(
  'id' => $doc_id,
);
$requisites = Indoc::loadBy($lb_params);

$hKontrag = "hidden";
$hSumma = "hidden";
$hObject  = "hidden";
$hTypeWork= "hidden";
$hMainIntendant= "hidden";
$hMounth= "hidden";

$rKontrag = "";
$rSumma = "";
$rObject  = "";
$rTypeWork= "";
$rMainIntendant= "";
$rMounth= "";

$docType = $doc_type;
if ( $docType == 2 || $docType == 3 || $docType == 5 || $docType == 9 || 
	$docType == 10 ||$docType == 11 ||$docType == 12 ||$docType == 13 ||
	$docType == 14 ||$docType == 15 ||$docType == 16 ||$docType == 18 ||
	$docType == 19 ||$docType == 26 ) {
	$hKontrag = "text";
	$rKontrag = "required";
	}
	
if ( $docType == 2 || $docType == 3 ||  $docType == 9 || 
	$docType == 10 ||$docType == 11 ||$docType == 12 ||$docType == 13 ||
	$docType == 23 ||$docType == 15 ||$docType == 16 ||$docType == 18 ||
	$docType == 19 ||$docType == 26 ) {
	$hSumma = "text";
	$rSumma = "required";
	}
if ( $docType == 2 || $docType == 3 || $docType == 5 || $docType == 9 || 
	$docType == 10 ||$docType == 11 ||$docType == 12 ||$docType == 13 ||
	$docType == 4 ||$docType == 15 ||$docType == 16 ||$docType == 18 ||
	$docType == 19 ||$docType == 20 ||$docType == 21 ||$docType == 22 ||
	$docType == 23 ||$docType == 26 ||$docType == 50 ||$docType == 51 ) {
	$hObject = "text";
	$rObject  = "required";
	}
if ( $docType == 4 || $docType == 20 ||
	$docType == 21 ||$docType == 22 ||
	$docType == 23 ||$docType == 51) {
	$hTypeWork = "text";
	$rTypeWork= "required";
	}
if ( $docType == 11 ||$docType == 12 ||
	$docType == 13 ||$docType == 15 ||
	$docType == 16 ) {
	$hMainIntendant = "text";
	$rMainIntendant= "required";
	}
if ( $docType == 11 ||$docType == 12 ||$docType == 13 ||
	$docType == 14 ||$docType == 15 ||$docType == 16 ||$docType == 18 ||
	$docType == 19 ||$docType == 20 ||$docType == 21 ||$docType == 22 ||
	$docType == 17 ||$docType == 51) {
	$hMounth = "text";
	$rMounth= "required";
	}

/*
//реквизиты -конец
*/
$html_object1  = $html_object;
$html_object1  = "oindocreq";
$form = Forms::Create()
    ->add("action", "action", "hidden", "action", $html_object1 . ".store.do", 6, false)
    // ->add("redirect", "redirect", "hidden", "redirect", "indocitems-list", 6, false)

    ->add("id", "id", "hidden", $html_object . "[id]", $oindoc_item->object->id)
    ->add("status_id", "status_id", "hidden", $html_object . "[params][status_id]", $status_id)
    ->add("user_id", "user_id", "hidden", $html_object . "[user_id]", $user_id)
    ->add("step_role", "step_role", "hidden", $html_object . "[step_role]", $step_role)
    ->add("doctypes", "Тип документа", "select", $html_object . "[params][doctypes]", $doc_type, 6, false, $DocTypesResult)
    ->add("name_doc", "Имя документа", "text", $html_object . "[name_doc]", $oindoc_item->object->name_doc)
    ->add("reg_number", "№ Регистрации", "hidden", $html_object . "[reg_number]", $reg_number, "", "", "", $disable_form)
    ->add("reg_date", "Дата регистрации", $hidden_date, $html_object . "[reg_date]", $regDate1)

	->add("req_id", "req_id", "hidden", $obj_requisites . "[id]", $doc_id)
    //->add("doc_id", "doc_id", "hidden", $obj_requisites . "[doc_id]", $doc_id)
	//->add("doc_id", "doc_id", "hidden", $obj_requisites . "[doc_id]", $oindoc_item->object->id)
	
    ->add("kontragent", "Контрагент", $hKontrag, $obj_requisites . "[kontragent]", htmlspecialchars($requisites->object->kontragent), "", $rKontrag)
	->add("summa", "Сумма", $hSumma, $obj_requisites . "[summa]", htmlspecialchars($requisites->object->summa), "", $rSumma)
	->add("curr_object", "Объект", $hObject, $obj_requisites . "[curr_object]", htmlspecialchars($requisites->object->curr_object), "", $rObject)
	->add("type_work", "Вид работ", $hTypeWork , $obj_requisites . "[type_work]", htmlspecialchars($requisites->object->type_work), "", $rTypeWork)
	->add("main_indendant", "Ответственный снабженец", $hMainIntendant, $obj_requisites . "[main_indendant]", htmlspecialchars($requisites->object->main_indendant), "", $rMainIntendant)
	->add("deadline", "Отчетный месяц", $hMounth, $obj_requisites . "[deadline]", htmlspecialchars($requisites->object->deadline), "", $rMounth)
    

    ->add("html", "", "html", "", '<src="' . CMS_TMP . SEP . $oindoc_item->object->params->file_title . '">')
    ->add("file", "Файл", "file", $html_object . "[file]")
    ->add("html", "", "html", "", $downloadBtn)
    ->add("html", "", "html", "", $relateddocs)
    ->parse();
?>


<div class="row">
    <div class="col-md-12 col-sm-12 ">
        <div class="x_panel">
            <div class="x_title">
                <h2>
                    ДОКУМЕНТЫ<small>форма редактирования</small>
                </h2>

                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card-box table-responsive">
                            <?= $form ?>
                            <!-- <div id="tags_1_tagsinput" class="tagsinput" style="width: auto; min-height: 100px; height: 100px;">
                                <span class="tag">
                                    <span><a href="#">social</a></span>
                                    <a href="#" title="Removing tag">x</a>
                                </span>
                                <span class="tag" style="background-color: #0069d9;" >
                                    <span><a href="#">Добавить</a></span>
                                </span>
                            </div> -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style lang="css">
    .dov{
        border-width: 1px;
    }
</style>

<!-- Modal - kontragents -->
<?
	Agents::setObject("oinfodocsagents");
	$where = Where::Cond()
		->add("_deleted", "=", "0")
		->parse();
	$items_agents = Agents::getList($where);
?>
<div class="modal fade" id="tableKontra" tabindex="-1" role="dialog" aria-labelledby="tableKontraLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tableKontraLabel">Контрагенты</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
		
        <!--<table  class="table table-striped table-bordered datatable" style="width:100%">-->
		<table  id="datatable" class="table table-striped table-bordered" style="width:100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Наименование</th>
					<th>Группа</th>
					<th>Материал</th>
					<th>Действие</th>
				</tr>
			</thead>
			<tbody>
				<?
					foreach ($items_agents as $item) :
				?>
				<tr>
					<td><?=$item->object->id?></td>
					<td><b><?= $item->object->name ?></b></td>
					<td><?= $item->object->group_ka ?></td>
					<td><?= $item->object->material ?></td>
					<td><a id="bttn-choise-kontra" class="btn btn-primary  btn-sm" data-id="<?=htmlspecialchars($item->object->id)?>" data-name="<?=htmlspecialchars($item->object->name) ?>" >Выбрать</a></td>
					
				</tr>
				<?endforeach;?>
			</tbody>
		</table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
    </div>
    </div>
  </div>
</div>

<!-- Modal - works -->
<?
	Works::setObject("oinfodocsworks");
	$where = Where::Cond()
		->add("_deleted", "=", "0")
		->parse();
	$items_works = Works::getList($where);
?>
<div class="modal fade" id="tableWork" tabindex="-1" role="dialog" aria-labelledby="tableWorkLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tableWorkLabel">Работы</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
		
        <!--<table  class="table table-striped table-bordered datatable" style="width:100%">-->
		<table  id="datatable" class="table table-striped table-bordered" style="width:100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Группа</th>
					<th>Наименование</th>
					<th>Измерение</th>
					<th>Действие</th>
				</tr>
			</thead>
			<tbody>
				<?
					foreach ($items_works as $item) :
				?>
				<tr>
					<td><?= $item->object->id ?></td>
					<td><?= $item->object->gruppa ?></td>
					<td><?= $item->object->name ?></td>
					<td><?= $item->object->izm ?></td>
					<td><a id="bttn-choise-works" class="btn btn-primary  btn-sm" data-id="<?=htmlspecialchars($item->object->id)?>" data-name="<?=htmlspecialchars($item->object->name) ?>" >Выбрать</a></td>
					
				</tr>
				<?endforeach;?>
			</tbody>
		</table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
    </div>
    </div>
  </div>
</div>

<!-- Modal - objects -->
<?
	Works::setObject("oinfodocsobjects");
	$where = Where::Cond()
		->add("_deleted", "=", "0")
		->parse();
	$items_objects = Works::getList($where);
?>
<div class="modal fade" id="tableObjects" tabindex="-1" role="dialog" aria-labelledby="tableObjectsLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="tableObjectsLabel">Работы</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
    <div class="modal-body">
		
        <!--<table  class="table table-striped table-bordered datatable" style="width:100%">-->
		<table  id="datatable" class="table table-striped table-bordered" style="width:100%">
			<thead>
				<tr>
					<th>ID</th>
					<th>Наименование</th>
					<th>Действие</th>
				</tr>
			</thead>
			<tbody>
				<?
					foreach ($items_objects as $item) :
				?>
				<tr>
					<td><?= $item->object->id ?></td>
					<td><?= $item->object->name ?></td>
					<td><a id="bttn-choise-objects" class="btn btn-primary  btn-sm" data-id="<?=htmlspecialchars($item->object->id)?>" data-name="<?=htmlspecialchars($item->object->name) ?>" >Выбрать</a></td>
					
				</tr>
				<?endforeach;?>
			</tbody>
		</table>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
    </div>
    </div>
  </div>
</div>


<script>

	jQuery(document).ready(function(){

			jQuery("#type_work").on("click", function(){
					jQuery('#tableWork').modal('show');
				
			})
			
			jQuery("#curr_object").on("click", function(){
					jQuery('#tableObjects').modal('show');
				
			})
			
			jQuery("#kontragent").on("click", function(){
					jQuery('#tableKontra').modal('show');
				
			})
			
			jQuery('a').on("click", function(){
				var id_html = jQuery(this).attr("id");
				if(id_html == 'bttn-choise-kontra') {
					var id = jQuery(this).attr("data-id");
					var name = jQuery(this).attr("data-name");
					//console.log(id);
					//console.log(name);
					//console.log(id_html);
					var value = id + " " + name;
					$('#kontragent').val(value);
					jQuery('#tableKontra').modal('hide');
				}
				if (id_html == 'bttn-choise-works') {
					var id = jQuery(this).attr("data-id");
					var name = jQuery(this).attr("data-name");
					//console.log(id);
					//console.log(name);
					//console.log(id_html);
					var value = id + " " + name;
					$('#type_work').val(value);
					jQuery('#tableWork').modal('hide');
				}
				if (id_html == 'bttn-choise-objects') {
					var id = jQuery(this).attr("data-id");
					var name = jQuery(this).attr("data-name");
					//console.log(id);
					//console.log(name);
					//console.log(id_html);
					var value = id + " " + name;
					$('#curr_object').val(value);
					jQuery('#tableObjects').modal('hide');
				}
				
				
			})
	
		}
	)
			
</script>
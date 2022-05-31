<?php

use \RedCore\Infodocs\Collection as Infodocs;
use \RedCore\Where as Where;

Infodocs::setObject("oinfodocsobjects");
$where = Where::Cond()
  ->add("_deleted", "=", "0")
  ->parse();
$items = Infodocs::getList($where);

?>
<script src="/core/view/desktop/Excel/UploadFile.js"></script>
	<div class="x_title">
        <h2>НОРМАТИВНО-СПРАВОЧНАЯ ДОКУМЕНТАЦИЯ<small>перечень объектов</small></h2>
        <div class="clearfix"></div>
     </div>
	 
<a class="btn btn-primary" href="/infodocs-objectsform">Добавить</a>
<!--<button class="btn btn-primary" onclick="ShowModalForUpload('works')">Загрузить данные из файла</button>-->
<table border=1 id="datatable" class="table table-striped table-bordered" style="width:100%">
	<thead>
		<tr>
			<th>id</th>
			<th>Наименование</th>
		
		</tr>
	</thead>
	<tbody>

<?
    foreach($items as $item):
?>

	<tr>
		<td><?= $item->object->id ?></td>
		<td><?= $item->object->name ?></td>
		<td>
            <div class="btn-group btn-group-sm">
                <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    Действия
                </button>
				<div class="dropdown-menu">
					<a class="dropdown-item" href="/infodocs-worksform?oinfodocsobjects_id=<?=$item->object->id?>">Редактировать</a>
					<div class="dropdown-divider"></div>
					<a class="dropdown-item" href="/infodocs-works?action=oinfodocsobjects.delete.do&oinfodocsobjects[id]=<?= $item->object->id ?>">Удалить</a>
				</div>
            </div>
        </td>
	</tr>
	
<?
endforeach;	

?>
</tbody>
</table>
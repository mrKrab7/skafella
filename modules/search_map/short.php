<?
	$data['navi'] = '';
	$data['classn'] = '';
	$data['classc'] = 'id="search"';	
	$data['content'] ="<form action='$url' method=post role=form>				
					 <div class='input-group'>
					<input type='text' name='searchstring' placeholder='поиск' class='form-control btn-search'>
					   <span class='input-group-btn'><input type=hidden  name=fullsearch value=1>
						<button value='&nbsp;' class='btn btn-search' type='submit'><span class='fa fa-search'>  </span></button>
					  </span>
					 </div>
				</form>";/**/
?>
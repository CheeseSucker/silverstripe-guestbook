<% loop $Smileys %>
	<a href="#" onclick="javascript:addSmiley('$Symbol', '$Up.FieldID'); return false;"
	   ><img src="$Image" alt="$Symbol" style="border:0" /></a>
<% end_loop %>

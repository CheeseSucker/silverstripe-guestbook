<% loop $Smileys %>
	<a href="#" onclick="Guestbook.addSmiley('$Symbol', '$Up.FieldID'); return false;"
	   ><img src="$Image.ATT" title="$Symbol.ATT" alt="$Symbol.ATT" style="border:0" /></a>
<% end_loop %>

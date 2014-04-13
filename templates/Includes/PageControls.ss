<% if $MoreThanOnePage %>
	<%t PageControls.PAGES "Pages:" %>

	<% if $NotFirstPage %>
		<a class="prev" href="$PrevLink"><%t PageControls.PREVIOUS "Previous" %></a>
	<% end_if %>
	<% loop $Pages %>
		<% if $CurrentBool %>
			$PageNum
		<% else %>
			<% if $Link %>
				<a href="$Link">$PageNum</a>
			<% else %>
				...
			<% end_if %>
		<% end_if %>
	<% end_loop %>
	<% if $NotLastPage %>
		<a class="next" href="$NextLink"><%t PageControls.NEXT "Next" %></a>
	<% end_if %>
<% end_if %>

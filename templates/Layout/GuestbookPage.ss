<% include SideBar %>
<% require css(guestbook/css/guestbook.css) %>
<% require javascript(guestbook/javascript/guestbook.js) %>

<div class="guestbook">
	<h1>$Title</h1>

	$Content

	<% if PaginatedEntries.Count == 0 %>
		<p class="no-entries-message">
			<%t GuestbookPage_ss.NOENTRIES "Be the first to sign this guestbook!" %>
		</p>
	<% end_if %>

	<% loop $PaginatedEntries %>
		<div class="entry">
			<div class="actions">
				<% if $Email %>
					<a href="$EmailURL" class="action email">
						<%t GuestbookPage_ss.EMAIL "E-mail" %>
					</a>
				<% end_if %>
				<% if $Website %>
					<a href="$Website" rel="popup" class="action website">
						<%t GuestbookPage_ss.WEBSITE "Website" %>
					</a>
				<% end_if %>
				<% if $Top.Moderator %>
					<a href='$EditLink' class="action edit">
						<%t GuestbookPage_ss.EDIT "Edit" %>
					</a>
				<% end_if %>
			</div>
			<div class="title">$Name</div>
			<div class="date">$Date</div>
			<div class="message">
				$FormattedMessage

			<% if $Comment %>
				<div class="comment">
					<strong><%t GuestbookPage_ss.ss.COMMENT "Comment [Administrator]:" %></strong><br />
					$FormattedComment
				</div>
			<% end_if %>
			</div>
		</div>
	<% end_loop %>	

	<p class="pagination">
		<% with PaginatedEntries %>
			<% include PageControls %>
		<% end_with %>
	</p>

	<p><%t GuestbookPage_ss.ENTRIES "Entries: <strong>{count}</strong>" count=$PaginatedEntries.Count %></p>

	<h2><%t GuestbookPage_ss.NEWENTRY "New entry" %></h2>
	$NewEntryForm
</div>

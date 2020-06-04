<div class="dashboard-commerce-panel dashboard-recent-orders-list">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th><%t SilverCommerce.Email "Date" %></th>
                <th><%t SilverCommerce.Total "Total" %></th>
            </tr>
        </thead>
        <tbody>
            <% loop Orders %>
                <tr>
                    <td><a href="{$AdminEditLink}">$OrderNumber</a></td>
                    <td>$Created.Nice</td>
                    <td>$Total.Nice</td>
                <tr>
            <% end_loop %>
        </tbody>
    </table>
</div>
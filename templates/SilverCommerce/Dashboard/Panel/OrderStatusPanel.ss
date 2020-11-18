<div class="dashboard-commerce-panel dashboard-order-status">
    <table>
        <tbody>
            <% loop $StatusCount %>
                <tr>
                    <th>$Status</th>
                    <td>$Count</td>
                </tr>
            <% end_loop %>
        </tbody>
    </table>
</div>
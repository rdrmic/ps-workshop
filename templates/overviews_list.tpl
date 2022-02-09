<div id="item-list-search-bar">
    <form method="post">
        <ul class="form-style-1">
            <li>
                <label class="overviews" style="display:inline;margin-right:1px;">Početni datum</label>
                <input type="date" name="start_date" value="[@start_date]" placeholder="0" class="datepicker" style="display:inline;margin-right:18px;width:133px;" />
                
                <label class="overviews" style="display:inline;margin-right:1px;">Završni datum</label>
                <input type="date" name="finish_date" value="[@finish_date]" placeholder="0" class="datepicker" style="display:inline;margin-right:52px;width:133px;" />
                
                <input type="submit" name="submit" value="Dohvati podatke" />
            </li>
        </ul>
    </form>
</div>
<div class="item-list overviews">
    <table>
        <thead>
            <th class="overviews">ŠIFRA</th>
            <th class="overviews">NAZIV</th>
            <th class="table_right_align overviews">CIJENA</th>
            <th class="table_right_align overviews">KOLIČINA</th>
            <th class="table_right_align overviews">VRIJEDNOST</th>
            <!--
            <th></th>
            -->
        </thead>
        <tbody id="tbody-overviews-added-items">
            [@item-list]
        </tbody>
    </table>
</div>

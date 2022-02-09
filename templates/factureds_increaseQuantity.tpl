<div id="item-add">
    <form method="post">
        <input type="hidden" name="id" value="[@id]" />

        <ul class="form-style-1">
            <li class="factureds">
                <label class="factureds">Fakturiranje</label>
                <input
                    type="text"
                    name="quantity_increase"
                    value="[@quantity_increase]"
                    placeholder="0"
                    class="field-middle right-align ctrl-int-gt-zero-required"
                />
                <span>
                    <a class="link-to-details" href="[@href]"> MAX [@max_possible_entry]</a>
                </span>
            </li>
            
            <li class="err_msg_to_user">[@msg]</li>

            <li class="button-bar">
                <input type="submit" name="submit" value="Unesi" onclick="return checkIncreaseQuantityInput();" />
                <button type="button" class="button-orange" onclick="document.location='?[@storage]';">Odustani</button>
            </li>
        </ul>
    </form>
</div>

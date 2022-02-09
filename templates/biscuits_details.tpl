<div id="item-detail" style="width: 850px;">
    <form method="post">
        <input type="hidden" name="id" id="biscuit-id" value="[@id]" />

        <ul class="form-style-1">
            <li>
                <label class="readonly">Šifra</label>
                <input type="text" name="code" value="[@code]" class="field-middle readonly" readonly="readonly" />
            </li>
            <li>
                <label class="readonly">Naziv</label>
                <input type="text" name="name" value="[@name]" class="field-long readonly" readonly="readonly" />
            </li>
            <li>
                <label><span class="not-required">*</span>Napomena</label>
                <textarea cols="35" rows="3" name="note" class="field-textarea">[@note]</textarea>
                <br /><br />
            </li>
            
            <li>
                <label id="label-work_price">Cijena rada po biskvitu</label>
                <input type="text" name="work_price" id="work_price" value="[@work_price]" placeholder="0.00" class="field-middle right-align" /> Kn
                <br /><br />
            </li>
            <li>
                <label id="label-materials">Materijali</label>
                <div class="building-items-selections-list">[@materials_list]</div>
                <div style="margin-top: 3px;">
                    <input type="text" name="materials_price" id="materials_price" value="[@materials_price]"
                        placeholder="0,00" class="field-middle right-align readonly" readonly="readonly"
                    /> Kn
                </div>
                <br />
            </li>
            <li>
                <label class="readonly">Ukupna cijena biskvita</label>
                <input type="text" name="price" id="price" value="[@price]" placeholder="0,00" class="field-middle right-align readonly" readonly="readonly" /> Kn
                <br />
            </li>
            
            <li class="button-bar">
                <input type="submit" id="submit" name="submit" value="Spremi" />
                <button type="button" class="button-orange" onclick="document.location='?biscuits';">Odustani</button>
            </li>
        </ul>
    </form>
</div>

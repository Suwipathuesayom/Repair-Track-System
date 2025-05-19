<div id="partsRequestModal"
    style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.4); z-index:1000;">
    <div
        style="background:white; max-width:500px; margin:10% auto; padding:20px; border-radius:10px; position:relative;">
        <h3>ร้องขออะไหล่</h3>
        <form method="POST" id="partsRequestForm">
            @csrf
            <input type="hidden" name="repair_id" id="parts_repair_id">

            <label for="part_id">ชื่ออะไหล่:</label>
            <select name="part_id" id="part_id" required
                style="width:100%; padding:8px; margin-bottom:10px; border:1px solid #ccc; border-radius:6px;">
                <option value="">-- เลือกอะไหล่ --</option>
                @foreach($parts as $part)
                    <option value="{{ $part->id }}">{{ $part->name }}</option>
                @endforeach
            </select>

            <label for="quantity">จำนวน:</label>
            <input type="number" name="quantity" required min="1"
                style="width:100%; padding:8px; border:1px solid #ccc; border-radius:6px;">

            <div style="text-align:right; margin-top:1rem;">
                <button type="button" onclick="closePartsModal()"
                    style="background-color: #ccc; padding:8px 16px; border-radius:6px;">ยกเลิก</button>
                <button type="submit"
                    style="background-color: #3498db; color:white; padding:8px 16px; border-radius:6px;">ส่งคำขอ</button>
            </div>
        </form>
    </div>
</div>
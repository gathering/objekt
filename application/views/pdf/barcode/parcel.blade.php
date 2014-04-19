<pdf>
    <page page-size="57.2mm:31.8mm">
        <p>
            <h1 font-size="3.4mm" margin-left="-7mm" margin-top="-15mm" line-break="false">{{ strtoupper($parcel->name) }}</h1>
            <barcode type="upca" font-size="3.4mm" margin-left="-5mm" bar-thin-width="2" bar-thick-width="2" bar-height="70" code="{{ $parcel->id }}" />
        </p>
    </page>
</pdf>
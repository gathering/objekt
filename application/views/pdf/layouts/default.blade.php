<pdf>
    <page page-size="a4" document-template="{{ pdf_asset('layouts/default_background.pdf') }}">
        <placeholders>
            <header>
                <div height="70px"></div>
            </header>
            <footer>
                <div height="190px"></div>
            </footer>
        </placeholders>
        @yield('content')
    </page>
</pdf>
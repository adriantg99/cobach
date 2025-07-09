{{-- ANA MOLINA 07/08/2023 --}}
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>
    @yield('title', config('app.name', 'SCE-COBACH'))
  </title>

  <!-- ================== BEGIN BASE CSS STYLE ================== -->

  <!--
  <link rel="preconnect" href="https://fonts.googleapis.com">

  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400,600;700;800&family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
-->

  {{-- <link href="{{  env('APP_URL') }}:8000/css/vendor.min.css" rel="stylesheet">
  <link href="{{  env('APP_URL') }}:8000/css/app.min.css" rel="stylesheet"> --}}
{{-- dd ("{{  env('APP_URL') }}:8000/css/vendor.min.css" ); --}}
{{-- dd ("{{  env('APP_URL') }}:8000/css/app.min.css" ); --}}
  {{-- <link href="{{ asset('css/vendor.min.css') }}" rel="stylesheet"> --}}
  {{-- <link href="{{ asset('css/app.min.css') }}" rel="stylesheet"> --}}
  <!-- ================== END BASE CSS STYLE ================== -->

  <style type="text/css">
    body{

      font-family: sans-serif;
       border-block: solid pink;
       margin: 52mm 10mm 20mm 10mm;
    }
    .bdy{
        margin: 0mm 0mm 0mm 0mm;
        font-size: 9px;
        border-block: solid yellow;
      }

    @page {
        margin: 0 0 0 0;
    }
    header {
      position: fixed;
      left: 10mm;
      top: 15mm;
      right: 10mm;
      /* background-color: #ddd; */
      text-align: center;
      border-block: solid blue;
      font-size: 10px;
    }
    header h1{
      margin: 1mm 0 0 0;
      border-block: solid green;
    }
    header h2{
      margin: 1mm 0 1mm 0;
      border-block:  gray;
    }

    footer {
      position: fixed;
      left: 10mm;
      bottom: 5mm;
      right: 10mm;
      font-size: 9px;
      border-block: solid red;
    }
    footer .page:after {
      content: counter(page);
    }
    footer table {
      width: 100%;
      border-block:  solid coral;
    }
    footer p {
      text-align: right;
    }
    footer .izq {
      text-align: left;
    }

    .salto {
        /* page-break-before: always; */
        page-break-after: always;
        margin: 50mm 0mm 1mm 0mm;

    }


    /* table, th, td {
  border: 1px solid black;
} */
  </style>
  @yield('css_pre')
  @livewireStyles
</head>
<body  >
    <header>
        <table style="width:100%">
                <tr>
                <td>
                  @if (isset($imagen_logo))
                  <img src="data:image/png;base64,{{ chunk_split(base64_encode($imagen_logo->imagen)) }}"  width="100px"  alt="Logo" class="logo">
                  @else
                  <img  src="../public/images/logocobachchico.png"  width="100px"  alt="Logo" class="logo">
                  @endif
                </td>

                <td style="text-align: center;">
                    <strong>COLEGIO DE BACHILLERES DEL ESTADO DE SONORA</strong><br>
                        <span><strong>@yield('reporte')</strong></span><br>
                        <span>@yield('encabezado')</span>
                </td>
                <td style="text-align: right;">
                    <?php
                    if (isset($this->alumno_id))
                    {
                        //dd($imagen_find);
                         if ($imagen_find->count()>0)
                        {
                            $row=$imagen_find[0];
                            ?>
                            <div class="img-container">
                                <div class="imageOne image">
                                    <img src="data:image/png;base64,{{ chunk_split(base64_encode($row->imagen)) }}" height="70"  alt="Logo" class="logo">
                                </div>
                            </div>
                        <?php }
                        else { ?>
                        <img  src="../public/images/logocobachchico.png"  width="100px"  alt="Logo" class="logo">
                        <?php
                        }
                    }
                    else { ?>
                    <img  src="../public/images/logocobachchico.png"  width="100px"  alt="Logo" class="logo">
                    <?php
                        }

                     ?>
                </td>
                </tr>

                <tr>
                    <td colspan="3" style=" font-size: 10px;">
                        @yield('encabezadodet')
                    </td>
                </tr>

           {{--  <tr>
                <td  >
                    <img  src="../../../../public/logocobachchico.png"  width="100" height="60" alt="Logo" class="logo">
                </td>
                <td>
                    <p>    </p>
                </td>
                <td>
                    <h4><strong>Colegio de Bachilleres del Estado de Sonora</strong><br>
                    <strong>@yield('reporte')</strong>@yield('encabezado')</h4>
                    <p style="font-size: 10px;">@yield('encabezado')</p>
                </td>
            </tr> --}}
        </table>
      </header>
      <section class="bdy">
        @yield('content')
      </section>
      <footer>
        <section>
            @yield('pie')
          </section>
          <br>
          <br>
        <table>
          <tr>
            <td>
                <p class="izq">
                  Sistema de Control Escolar
                </p>
            </td>
            <td>
              <p class="page">
                Página
              </p>
            </td>
          </tr>
        </table>
      </footer>
<!-- BEGIN #app -->
{{-- <div id="app" class="app app-content-full-height app-footer-fixed ">

  <div id="content" class="app-content">

    <div class="container-fluid ">--}}

      <!-- BEGIN #header -->



      {{-- <div id="header" class="app-header">
      </div> --}}
      <!-- END #header -->

      {{-- <section class="py-3">
        @yield('content')
      </section> --}}

    {{-- </div>
  </div>


</div> --}}
<!-- END #app -->

</body>
</html>

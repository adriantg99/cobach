{{-- ANA MOLINA 07/08/2023 --}}
<html lang="es">
<head>
  <meta charset="utf-8" />
  <title>
    @yield('title', config('app.name', 'SCE-COBACH'))
  </title>
  <style type="text/css">
    body{

      font-family: sans-serif;
       border-block: solid pink;
       margin: 48mm 15mm 20mm 15mm;
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
      left: 15mm;
      top: 15mm;
      right: 15mm;
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
      left: 15mm;
      bottom: 5mm;
      right: 15mm;
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

          </tr>
        </table>
      </footer>
</body>
</html>

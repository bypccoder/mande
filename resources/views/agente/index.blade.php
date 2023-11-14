<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- PAGE TITLE HERE -->
    <title>{{ config('app.name', 'Cafeteria') }}</title>

    <!-- GOOGLE FONTS -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Oswald:wght@200;300;400;500;600;700&display=swap"
        rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">

    <!-- FAVICONS ICON -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet"
        href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
    <link href="{{ url('assets/') }}/css/style.css" rel="stylesheet">
    <link href="{{ url('assets/') }}/css/app.css" rel="stylesheet">


</head>

<body>
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="bg-categories">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12 text-center m-auto content-auth">
                        <div class="tab-content">
                            <div class="tab-pane active position-relative" id="auth" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="auth-form">

                                    <h5 class="text-light">para generar tu pedido</h5>
                                    <h2 class="h2-xl text-white">Ingresa tu Documento</h2>
                                    <div class="input-group mt-5">
                                        <input id="document" class="form-control document" type="text"
                                            placeholder="...">
                                    </div>
                                    <a id="validate-document"
                                        class="btn btn-lg btn-salmon tra-salmon-hover mt-4">CONTINUAR <i
                                            class="las la-arrow-right"></i></a>
                                </div>
                            </div>
                            <div class="tab-pane position-relative" style="width: 650px" id="register" role="tabpanel"
                                aria-labelledby="profile-tab">
                                <div class=" auth-form">
                                    <form id="registrationForm">
                                        <input type="hidden" id="person_id" name="person_id">
                                        <div class="mb-3">
                                            <label for="nombres" class="fs-5 form-label text-white">Nombres:</label>
                                            <input type="text" class="form-control" name="name" id="name"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="apellidos" class="fs-5 form-label text-white">Apellidos
                                                Paterno:</label>
                                            <input type="text" class="form-control" name="lastname_1" id="lastname_1"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="apellidos" class="fs-5 form-label text-white">Apellidos
                                                Materno:</label>
                                            <input type="text" class="form-control" name="lastname_2" id="lastname_2"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="document_type_id" class="fs-5 form-label text-white">Tipo de
                                                Documento:</label>
                                            <select class="form-select" name="document_type_id" id="document_type_id"
                                                required>
                                                <option value="">Seleccione...</option>
                                                @foreach ($documentTypes as $documentType)
                                                    <option value="{{ $documentType->id }}">
                                                        {{ $documentType->document }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="document" class="fs-5 form-label text-white">Número de
                                                Documento:</label>
                                            <input type="text" class="form-control" name="document" id="document"
                                                required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="email" class="fs-5 form-label text-white">Email:</label>
                                            <input type="email" class="form-control" name="email" id="email"
                                                required>
                                        </div>
                                    </form>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="#auth" class="btn btn-salmon tra-salmon-hover btn-tabs"
                                        onclick="changeTab(this)" data-coreui-toggle="tab" data-coreui-target="#auth"><i
                                            class="las la-arrow-left"></i> Volver</a>
                                    <a id="frmRegisterSend" class="btn btn-salmon tra-salmon-hover btn-tabs"><i
                                            class="las la-arrow-right"></i> Continuar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- #/ container -->
    <!-- Common JS -->
    <script src="{{ url('assets/') }}vendors/@coreui/coreui/js/coreui.bundle.min.js"></script>
    <script src="{{ url('assets/') }}vendors/simplebar/js/simplebar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.10/dist/sweetalert2.all.min.js"></script>

    <script src="{{ url('js/util.js') }}"></script>

    <script>
        const tabAuth = document.querySelector('#auth')
        const tabRegister = document.querySelector('#register')
        const frmRegister = document.querySelector('#registrationForm')        

        const FORM = {
            validateDocument: (response) => {
                if (response.code == 200) {
                    window.location.href = "{{ route('home') }}"
                } else if (response.code == 500) {

                    // Validacion en caso exista 2 registros en diferentes bases
                    mostrarMensajeAlerta(response.code, response.message)
                    if (response.status == 'danger')  return 0

                    if(response.status == 'update-email'){
                        tabAuth.classList.toggle('active')
                        tabRegister.classList.toggle('active')
                        //Llenar campos
                        document.querySelector('#registrationForm #person_id').value = response.data.id
                        document.querySelector('#registrationForm #name').value = response.data.name
                        document.querySelector('#registrationForm #lastname_1').value = response.data.lastname_1
                        document.querySelector('#registrationForm #lastname_2').value = response.data.lastname_2
                        document.querySelector('#registrationForm #document_type_id').value = response.data.document_type_id
                        document.querySelector('#registrationForm #document').value = response.data.number_document

                        //Bloquear campos
                        document.querySelector('#registrationForm #name').setAttribute('disabled',true);
                        document.querySelector('#registrationForm #lastname_1').setAttribute('disabled',true);
                        document.querySelector('#registrationForm #lastname_2').setAttribute('disabled',true);
                        document.querySelector('#registrationForm #document_type_id').setAttribute('disabled',true);
                        document.querySelector('#registrationForm #document').setAttribute('disabled',true);

                    }else{
                        frmRegister.querySelectorAll('input').forEach(el => {
                            el.removeAttribute('disabled')
                            el.value = ''
                        })
                        frmRegister.querySelector('select').removeAttribute('disabled')

                        tabAuth.classList.toggle('active')
                        tabRegister.classList.toggle('active')
                    }

                }
            },
            registerPerson: (form) => {
                let formData = new FormData(form)

                const formDataJSON = {};
                for (const [key, value] of formData) {
                    formDataJSON[key] = formData.get(key);
                }

                CRUD.create('client/person', formDataJSON).then((response) => {
                    if (response.code == 200) {
                        //emptyCart()

                        window.location.href = 'client/home'
                    } else {
                        mostrarMensajeAlerta(response.code)
                    }
                })
            }
        }


        document.getElementById('validate-document').addEventListener('click', function(e) {
            e.preventDefault()
            var documento = document.getElementById('document').value;

            CRUD.sendData('client/validate', {
                documento: documento
            }).then((response) => {
                FORM.validateDocument(response);
            })
        });

        document.getElementById('frmRegisterSend').addEventListener('click', (e) => {
            e.preventDefault()
            FORM.registerPerson(frmRegister)
        })

        function changeTab(element) {
            let id = element.getAttribute('href').slice(1)
            tabAuth.classList.toggle('active')
            tabRegister.classList.toggle('active')
        }
    </script>
</body>

</html>

<?php

class CartController extends Controller
{
    private $model;

    public function __construct()
    {
        $this->model = $this->model('Cart');
    }

    public function addProduct($product_id, $user_id)
    {
        $errors = [];
        if ( ! $this->model->verifyProduct($product_id, $user_id)) {
            if ( ! $this->model->addProduct($product_id, $user_id)) {
                array_push($errors, 'Error al insertar el producto en el carrito');
            }
        }
        $this->index($errors);
    }

    public function index($errors = [])
    {
        $session = new Session();

        if ($session->getLogin()) {
            $user_id = $session->getUserId();
            $cart = $this->model->getCart($user_id);
            $data = [
                'titulo'    => 'Carrito',
                'menu'      => true,
                'user_id'   => $user_id,
                'data'      => $cart,
                'errors'    => $errors,
            ];
            $this->view('carts/index', $data);
        } else {
            header('location:'.ROOT);
        }
    }

    public function update()
    {
        if (isset($_POST['rows']) && isset($_POST['user_id'])) {
            $errors = [];
            $rows = $_POST['rows'];
            $user_id = $_POST['user_id'];
            for ($i = 0; $i < $rows; $i++) {
                $product_id = $_POST['i'.$i];
                $quantity = $_POST['c'.$i];
                if( ! $this->model->update($user_id, $product_id,$quantity)) {
                    array_push($errors, 'Error al actualizar el producto ' . $i+1);
                }
            }
            $this->index($errors);
        }
    }

    public function delete($product, $user)
    {
        $errors = [];
        if ( ! $this->model->delete($product, $user)) {
            array_push($errors, 'Error al borrar el registro en el carrito');
        }
        $this->index($errors);
    }

    public function checkout()
    {
        $session = new Session();
        if ( $session->getLogin()) {
            $user = $session->getUser();
            $data = [
                'titulo'    => 'Carrito - Datos de env??o',
                'subtitle'  => 'Carrito - Verificar direcci??n de env??o',
                'menu'      => true,
                'data'      => $user,
            ];
            $this->view('carts/address', $data);
        } else {
            $data = [
                'titulo'    => 'Carrito - Checkout',
                'subtitle'  => 'Checkout - Iniciar sesi??n',
                'menu'      => true,
            ];
            $this->view('carts/checkout', $data);
        }
    }

    public function paymentmode()
    {
        // Procesar los datos del formulario
        $data = [
            'titulo'    => 'Carrito | Forma de pago',
            'subtitle'  => 'checkout | Forma de pago',
            'menu'      => true,
        ];
        $this->view('carts/paymentmode', $data);
    }

    public function verify()
    {
        $session = new Session();
        $user = $session->getUser();
        $cart = $this->model->getCart($user->id);
        $payment = $_POST['payment'] ?? '';
        $data = [
            'titulo'    => 'Carrito | verificar los datos',
            'subtitle'    => 'Carrito | verificar los datos',
            'payment'   => $payment,
            'user'      => $user,
            'data'      => $cart,
            'menu'      => true,
        ];
        $this->view('carts/verify', $data);
    }

    public function thanks()
    {
        $session = new Session();
        $user = $session->getUser();
        if ($this->model->closeCart($user->id, 1)) {
            $data = [
                'titulo'    => 'Carrito | Gracias por su compra',
                'data'      => $user,
                'menu'      => true,
            ];
            $this->view('carts/thanks', $data);
        } else {
            $data = [
                'titulo'    => 'Error durante la actualizaci??n del carrito',
                'menu'      => false,
                'subtitle'  => 'Error en la actualizaci??n de los productos del carrito',
                'text'      => 'Existi?? un problema al actualizar el estado del carrito. Por favor pruebe m??s tarde o comun??quese con nuestro servicio de soporte',
                'color'     => 'alert-danger',
                'url'       => 'login',
                'colorButton' => 'btn-danger',
                'textButton' => 'Regresar',
            ];
            $this->view('mensaje', $data);
        }
    }

    public function sales()
    {
        $sales = $this->model->sales();
        $data = [
            'titulo'    => 'Ventas',
            'menu'      => false,
            'admin'     => true,
            'data'      => $sales,
        ];
        $this->view('admin/carts/index', $data);
    }

    public function show($date, $id)
    {
        $cart = $this->model->show($date, $id);
        $data = [
            'titulo'    => 'Detalle de ventas',
            'menu'      => false,
            'admin'     => true,
            'data'      => $cart,
        ];
        $this->view('admin/carts/show', $data);
    }

    public function chartDailySales()
    {
        $sales = $this->model->dailySales();
        $data = [
            'titulo'    => 'Ventas diarias',
            'menu'      => false,
            'admin'     => true,
            'data'      => $sales,
        ];
        $this->view('admin/carts/dailysales', $data);
    }
}

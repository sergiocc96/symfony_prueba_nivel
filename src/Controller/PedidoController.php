<?php

namespace App\Controller;

use App\Entity\Pedido;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class PedidoController extends AbstractController
{
    /**
     * @Route("/", name="pedido")
     */
    public function index(): Response
    {
        /*$product = $this->getDoctrine()->getRepository(Producto::class)->getProducto();

        if (!$product) {
            throw $this->createNotFoundException(
                'No product found for id '
            );
        }

        return new Response(json_encode($product));
*/

        return $this->render('pedido/index.html.twig', [
            'controller_name' => 'PedidoController',
        ]);
    }

    /**
     * @Route("/get_pedido", name="get_pedido")
     */
    public function get_pedido(Request $request)
    {

        if($request->request->get('id_pedido')){

            try {
                $product = $this->getDoctrine()->getRepository(Pedido::class)->getPedido($request->request->get('id_pedido'));
                //make something curious, get some unbelieveable data
                $data_response['data'] = $product;
                $data_response['success'] = true;


                if(empty($product)){

                    $data_response['success'] = false;
                    $data_response['data'] = 'No existe ningun pedido con ese ID. ';

                    return new JsonResponse($data_response,404);

                }

                return new JsonResponse($data_response,200);

            }catch (\Exception $e){

                $data_response['success'] = false;
                return new JsonResponse($data_response,500);

            }

        }

        return new JsonResponse('Error no contiene id_pedido',400);
    }


    /**
     * @Route("/tabla_pedidos", name="tabla_pedidos")
     */
    public function renderTablaPedidos(): Response
    {

        $estados = $this->getDoctrine()->getRepository(Pedido::class)->getEstados();


        return $this->render('pedido/tabla_pedidos.twig', [
            'controller_name' => 'TablaPedidosController',
            'estados' => $estados
        ]);
    }

    /**
     * @Route("/get_pedidos", name="get_pedidos")
     */
    public function get_pedidos(Request $request)
    {


            $product = $this->getDoctrine()->getRepository(Pedido::class)->getPedidos($request->request->all());
            //make something curious, get some unbelieveable data

            if(empty($product)){
                return new JsonResponse('No existe ningun pedido con ese ID. ',404);

            }
            return new JsonResponse($product,200);

    }
}

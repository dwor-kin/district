<?php
namespace App\Controller;

use App\Component\Form\DistrictCreateType;
use App\Component\Form\DistrictUpdateType;
use App\Enum\CityEnum;
use App\Enum\DistrictEnum;
use App\Helper\FilterMapper;
use App\Service\DistrictProvider;
use App\Service\DistrictHandler;
use App\Service\Migration\DistrictDbMigration;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class DistrictController extends AbstractController
{
    /**
     * Route("/district", name="district_home")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function district()
    {
        return $this->redirectToRoute('district_list');
    }

    /**
     * @Route("/district/list", name="district_list")
     * @param Request $request
     * @param DistrictProvider $districtProvider
     * @param SessionInterface $session
     * @return Response
     */
    public function list(Request $request, DistrictProvider $districtProvider, SessionInterface $session)
    {
        $sortType = $request->query->get('sortType', 'ASC');
        $sortField = $request->query->get('sortField', 'districtName');
        $filter = $request->query->get('filter', []);

        $filterConverted = FilterMapper::convertFromUrlToArray($filter);

        if (!empty($filter)) {
            $session->set('filters', $filterConverted);
        }

        if (!$request->query->get('filter')) {
            $filterConverted = (!empty($session->get('filters'))
                ? $session->get('filters')
                : FilterMapper::getFilterDefinition()
            );
        }

        $districtCollection = $districtProvider->getDistrictData($sortField, $sortType, $filterConverted);

        return $this->render('district.html.twig', [
            'list'              => $districtCollection,
            'sortType'          => $sortType == 'ASC' ? 'DESC' : 'ASC',
            'sortTypePresent'   => $sortType,
            'sortField'         => $sortField,
            'filter'            => $filterConverted
        ]);
    }

    /**
     * @Route("/district/single", name="get_single_district", methods={"GET"})
     * @param DistrictProvider $districtProvider
     * @param Request $request
     * @return JsonResponse
     */
    public function getSingleDistrict(DistrictProvider $districtProvider, Request $request)
    {
        try {
            $district = $districtProvider->getSingleDistrictData($request->query->get('id'));

            return new JsonResponse(array(
                'success' => true,
                'district' => $district
            ), 200);
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'message'  => $e->getMessage()
            ), 200);
        }
    }

    /**
     * @Route("/district/remove/{id}", name="remove", methods={"DELETE"})
     * @param DistrictHandler $districtHandler
     * @param $id
     * @return mixed
     */
    public function remove(DistrictHandler $districtHandler, $id): JsonResponse
    {
        try {
            $districtHandler->handleRemoval($id);
            return new JsonResponse(array(
                'success' => true
            ), 200);
        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'message'  => $e->getMessage()
            ), 200);
        }
    }

    /**
     * @Route("/district/create", name="create", methods={"POST"})
     * @param Request $request
     * @param DistrictHandler $districtHandler
     * @return JsonResponse
     */
    public function create(Request $request, DistrictHandler $districtHandler): JsonResponse
    {
        $form = $this->createForm(DistrictCreateType::class, null, ['method' => 'POST']);
        $form->handleRequest($request);

        try {
            if (!$form->isValid()) {
                throw new \Exception('wrong parameters');
            }

            $districtHandler->createNew($form);
            return new JsonResponse(array(
                'success' => true
            ), 200);

        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'message'  => $e->getMessage()
            ), 200);
        }
    }

    /**
     * @Route("/district/update", name="update", methods={"POST"})
     * @param Request $request
     * @param DistrictHandler $districtHandler
     * @return JsonResponse
     */
    public function update(Request $request, DistrictHandler $districtHandler): JsonResponse
    {
        $form = $this->createForm(DistrictUpdateType::class, null, ['method' => 'POST']);
        $form->handleRequest($request);

        try {
            if (!$form->isValid()) {
                throw new \Exception('wrong parameters');
            }

            $districtHandler->update($form);
            return new JsonResponse(array(
                'success' => true
            ), 200);

        } catch (\Exception $e) {
            return new JsonResponse(array(
                'success' => false,
                'message'  => $e->getMessage()
            ), 200);
        }
    }

    /**
     * @Route("/district/import", name="import", methods={"GET"})
     * @param Request $request
     * @param DistrictDbMigration $districtDbMigration
     * @return RedirectResponse
     */
    public function importDefault(Request $request, DistrictDbMigration $districtDbMigration): RedirectResponse
    {
        $importCity = $request->query->get('city');

        $cities = empty($importCity)
            ? [CityEnum::CITY_GDANSK, CityEnum::CITY_KRAKOW]
            : [$importCity];

        try {
            $districtDbMigration->migrateFromTheCity($cities);
            return $this->redirectToRoute('district_list');

        } catch (\Exception $e) {
            return $this->redirectToRoute('district_list');
        }
    }

    /**
     * @Route("/district/purge/imported", name="purge_imported", methods={"GET"})
     * @param DistrictHandler $districtHandler
     * @return RedirectResponse
     */
    public function purgeImported(DistrictHandler $districtHandler)
    {
        try {
            $districtHandler->purge(DistrictEnum::DISTRICT_IMPORTED);
            return $this->redirectToRoute('district_list');
        } catch (\Exception $e) {
            // some error into logs
            return $this->redirectToRoute('district_list');
        }
    }

    /**
     * @Route("/district/purge/inserted", name="purge_inserted", methods={"GET"})
     * @param DistrictHandler $districtHandler
     * @return RedirectResponse
     */
    public function purgeInserted(DistrictHandler $districtHandler)
    {
        try {
            $districtHandler->purge(DistrictEnum::DISTRICT_INSERTED);
            return $this->redirectToRoute('district_list');
        } catch (\Exception $e) {
            // some error into logs
            return $this->redirectToRoute('district_list');
        }
    }
}
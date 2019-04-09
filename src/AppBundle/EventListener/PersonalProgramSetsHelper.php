<?php

namespace AppBundle\EventListener;

class PersonalProgramSetsHelper {
    protected function setPersonalProgram($event) {

        $user = $event->getAuthenticationToken()->getUser();
        $session = $this->container->get('session');
        $personalProgram = $session->get('personalProgramId');
        print_r($personalProgram);die('a');
        //print_r($personalProgram);die;
        if($personalProgram && $personalProgram = $this->getPersonalProgramById($personalProgram, $user)) {

            if($exists = $this->getPersonalProgramByUserId($user->getId())) {
                $this->removePersonalProgram($exists);
            }

            $personalProgram->setUserRefId($user);
            $this->em->persist($personalProgram);
            $this->em->flush();
        }
    }

    protected function getPersonalProgramById($id, $user) {
        $program = $this->getDoctrine()
            ->getRepository(PersonalProgram::class)
            ->findById($id);

        return $program ? end($program) : null;
    }

    protected function getPersonalProgramByUserId($id) {
        $program = $this->getDoctrine()
            ->getRepository(PersonalProgram::class)
            ->findByUserRefId($id);

        return $program ? end($program) : null;;
    }

    protected function getDoctrine() {
        return $this->container->get('doctrine');
    }

    protected function removePersonalProgram($personalProgram) {
        $entityManager = $this->em;

        foreach($personalProgram->getLifeStyle() as $style) {
            $entityManager->remove($style);
        }

        $entityManager->remove($personalProgram);
        $entityManager->flush();
    }
}
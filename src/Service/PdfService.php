<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
/*
use Dompdf\Dompdf;
use Symfony\Component\File\UploadedFile;
use Symfony\Flex\Options;

*/
class PdfService
{
    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectory()
    {
        return $this->targetDirectory;
    }



/*
    private Dompdf $DomPdf;
   // private dompdf;

    public function __construct() {
        $this->DomPdf = new Dompdf();

        $pdOption =new Dompdf();
        $dompdf = new DOMPDF();


        // $pdOption->set('defaultFont', 'Garamond');
        $pdfOptions = new Options();


        $this->DomPdf->setOptions($pdOption);



    }
*/
    /**
     * @return Dompdf
     */
    /*
    public function getDompPdf(): Dompdf
    {
        return $this->dompPdf;
    }
*/
    /**
     * @param Dompdf $DomPdf
     */
    /*
    public function setDomPdf(Dompdf $DomPdf): void
    {
        $this->DomPdf = $DomPdf;
    }
    //fonction permet d'afficer le pdf au niveau de navigateur
    public function showPdfFile($html)
    {
        $this->dompPdf->loadHtml($html);
        $this->dompPdf->render();
        $this->dompPdf->stream("details.pdf",[
            'Attachment' => false
        ]);

    }

    public function generateBinaryPDF($html)
    {
        $this->dompPdf->loadHtml($html);
        $this->dompPdf->render();
        $this->dompPdf->output();

    }
    */


}
<?php
namespace App\Component\Form;

use Doctrine\DBAL\Types\FloatType;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DistrictFullType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('districtName', TextType::class, ['required' => true, 'error_bubbling' => true])
            ->add('cityId', NumberType::class, ['required' => true, 'error_bubbling' => true])
            ->add('area', NumberType::class, ['required' => true, 'error_bubbling' => true])
            ->add('population', NumberType::class, ['required' => false, 'error_bubbling' => true]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
        ]);
    }

    public function getBlockPrefix()
    {
        return null;
    }
}

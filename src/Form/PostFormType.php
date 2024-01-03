<?php

namespace App\Form;

use App\Entity\Post;
use App\Entity\Category;
use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class PostFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class)
            ->add('imageFile',VichImageType::class, [
                'required' => false,
                'allow_delete' => true,
                'delete_label' => 'Supprimer',
                'download_label' => false,
                'download_uri' => false,
                'image_uri' => false,
                'imagine_pattern' => false,
                'asset_helper' => true,
            ])
            ->add('content', TextareaType::class)
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'class' => Category::class,
                'placeholder' => 'Choisissez une catégorie',
            
                // uses the Category.name property as the visible option string
                'choice_label' => 'name',
            
            ])
            ->add('tags', EntityType::class, [
                // looks for choices from this entity
                'class' => Tag::class,
                'placeholder' => 'Choisissez une catégorie',
            
                // uses the Category.name property as the visible option string
                'choice_label' => 'name',
                // used to render a select box, check boxes or radios
                'multiple' => true,
                'expanded' => false,
            
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}

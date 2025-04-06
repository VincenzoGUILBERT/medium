<?php

namespace App\Form;

use App\Entity\Post;
use App\Form\Type\TagsInputType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PostType extends AbstractType
{
    public function __construct(private Security $security) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content')
            ->add('tags', TagsInputType::class, [
                'label' => 'Tags',
                'required' => false,
                'attr' => [
                    'data-ub-tag-separator' => " ",
                    'data-ub-tag-max' => 4,
                    'data-ub-tag-variant' => "primary"
                ]
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, $this->setPostAuthor(...))
        ;
    }

    public function setPostAuthor(FormEvent $formEvent): void
    {
        $post = $formEvent->getData();

        if ($post->getId() === null) {
            $post->setAuthor($this->security->getUser());
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}

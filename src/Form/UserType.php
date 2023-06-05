<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        /**
         * @var User
         */
        $user= $builder->getData();

        $builder
            ->add('email', EmailType::class, ['label'=>'*Email'])
            ->add('firstname', TextType::class, ['label'=> '*Prenom'])
            ->add('lastname',TextType::class, ['label'=> '*Nom'])
            ->add('pictureFile',FileType::class, [
                'label'=> 'Image',
                // 'required' => $user?->getPicture() ? false : true,
                // 'required' => $options['new_user'],
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new Image([
                        'mimeTypesMessage' => 'Veuillez uploader une image.',
                        'maxSize' => '1M',
                        'maxSizeMessage' =>'Votre image fait {{ size }} {{ suffix }}. La limite est de {{ limit }} '
                    ])
                ]
            
            ])
            ->add('password', PasswordType::class, ['label'=> '*Mot de passe'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'new_user' => true
        ]);
    }
}

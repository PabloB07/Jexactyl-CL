import React from 'react';
import tw from 'twin.macro';
import { object, string, setLocale } from 'yup';
import { pt } from 'yup-locales';
import styled from 'styled-components';
import useFlash from '@/plugins/useFlash';
import { useRouteMatch } from 'react-router';
import { httpErrorToHuman } from '@/api/http';
import { createMessage } from '@/api/account/tickets';
import { Textarea } from '@/components/elements/Input';
import { Field, Form, Formik, FormikHelpers } from 'formik';
import { Button } from '@/components/elements/button/index';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import { Dialog, DialogProps } from '@/components/elements/dialog';
import FormikFieldWrapper from '@/components/elements/FormikFieldWrapper';

interface Values {
    description: string;
}

const CustomTextarea = styled(Textarea)`
    ${tw`h-32`}
`;

export default ({ open, onClose }: DialogProps) => {
    const match = useRouteMatch<{ id: string }>();
    const id = parseInt(match.params.id);

    const { addError, clearFlashes, addFlash } = useFlash();

    setLocale(pt);

    const submit = (values: Values, { setSubmitting, resetForm }: FormikHelpers<Values>) => {
        clearFlashes('tickets');

        createMessage(id, values.description)
            .then(() => {
                resetForm();
                setSubmitting(false);

                addFlash({
                    key: 'tickets',
                    type: 'success',
                    message: 'Tu mensaje fue enviado con éxito!',
                });
            })
            .catch((error) => {
                setSubmitting(false);

                addError({ key: 'tickets', message: httpErrorToHuman(error) });
            });
    };

    return (
        <Dialog
            open={open}
            onClose={onClose}
            title={'Añadir un mensaje'}
            description={'Este mensaje será visible tanto para usted como para los administradores de este Panel.'}
        >
            <Formik
                onSubmit={submit}
                initialValues={{ description: '' }}
                validationSchema={object().shape({
                    description: string().required().min(4),
                })}
            >
                {({ isSubmitting }) => (
                    <Form className={'mt-6'}>
                        <SpinnerOverlay visible={isSubmitting} />
                        <FormikFieldWrapper
                            label={'Descripción'}
                            name={'description'}
                            description={
                                'Proporcione información adicional, imágenes y otro contenido para ayudarnos a resolver su problema más rápido..'
                            }
                        >
                            <Field name={'description'} as={CustomTextarea} />
                        </FormikFieldWrapper>
                        <div className={'flex justify-end mt-6'}>
                            <Button type={'submit'}>Enviar</Button>
                        </div>
                    </Form>
                )}
            </Formik>
        </Dialog>
    );
};

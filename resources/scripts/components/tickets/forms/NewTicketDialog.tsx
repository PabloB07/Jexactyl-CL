import React from 'react';
import tw from 'twin.macro';
import { object, string, setLocale } from 'yup';
import { pt } from 'yup-locales';
import styled from 'styled-components';
import useFlash from '@/plugins/useFlash';
import { httpErrorToHuman } from '@/api/http';
import { createTicket } from '@/api/account/tickets';
import { Field, Form, Formik, FormikHelpers } from 'formik';
import { Button } from '@/components/elements/button/index';
import Input, { Textarea } from '@/components/elements/Input';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import { Dialog, DialogProps } from '@/components/elements/dialog';
import FormikFieldWrapper from '@/components/elements/FormikFieldWrapper';

interface Values {
    title: string;
    description: string;
}

const CustomTextarea = styled(Textarea)`
    ${tw`h-32`}
`;

export default ({ open, onClose }: DialogProps) => {
    const { addError, clearFlashes } = useFlash();

    setLocale(pt);

    const submit = (values: Values, { setSubmitting, resetForm }: FormikHelpers<Values>) => {
        clearFlashes('tickets');

        createTicket(values.title, values.description)
            .then((data) => {
                resetForm();
                setSubmitting(false);

                // @ts-expect-error this is valid
                window.location = `/tickets/${data.id}`;
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
            title={'Crear un nuevo ticket'}
            description={'Este ticket se registró en su cuenta y es accesible para todos los administradores del Panel'}
        >
            <Formik
                onSubmit={submit}
                initialValues={{ title: '', description: '' }}
                validationSchema={object().shape({
                    allowedIps: string(),
                    description: string().required().min(4),
                })}
            >
                {({ isSubmitting }) => (
                    <Form className={'mt-6'}>
                        <SpinnerOverlay visible={isSubmitting} />
                        <FormikFieldWrapper
                            label={'Título'}
                            name={'title'}
                            description={'Un título para este ticket.'}
                            className={'mb-6'}
                        >
                            <Field name={'title'} as={Input} />
                        </FormikFieldWrapper>
                        <FormikFieldWrapper
                            label={'Descripción'}
                            name={'description'}
                            description={
                                'Proporcione información adicional, imágenes y otro contenido para ayudarnos a resolver su problema más rápidamente.'
                            }
                        >
                            <Field name={'description'} as={CustomTextarea} />
                        </FormikFieldWrapper>
                        <div className={'flex justify-end mt-6'}>
                            <Button type={'submit'}>Crear</Button>
                        </div>
                    </Form>
                )}
            </Formik>
        </Dialog>
    );
};

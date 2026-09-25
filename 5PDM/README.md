# 🦉 CorujaDocs

**Seus documentos acadêmicos em um só lugar.**

> ⚠️ **Protótipo acadêmico independente.** O CorujaDocs é um projeto de
> demonstração inspirado conceitualmente no sistema acadêmico Coruja da
> FAETERJ-RIO. Ele **não** acessa, faz scraping, login ou usa qualquer dado
> real do sistema oficial. Todos os dados de estudante, documentos e
> credenciais utilizados aqui são **fictícios**, e nenhum documento gerado
> possui validade oficial ou jurídica — todos trazem o aviso "DOCUMENTO
> DEMONSTRATIVO — PROTÓTIPO".

---

## 1. O que é o CorujaDocs

O CorujaDocs é um aplicativo mobile (React Native + Expo + TypeScript) que
propõe uma experiência moderna para consulta de dados acadêmicos e emissão de
documentos estudantis, com foco especial em um fluxo completo de:

**emissão de um documento → código de autenticidade único → validação por
terceiros.**

## 2. Objetivo

Permitir que o estudante:

- faça login;
- visualize seus dados acadêmicos;
- consulte e solicite documentos;
- emita uma **Declaração de Estudante** em PDF;
- baixe e compartilhe o PDF;
- copie o código de autenticidade do documento;
- e que **qualquer pessoa** (com ou sem login) valide esse documento a partir
  do código de autenticidade ou do QR Code impresso nele.

## 3. Tecnologias

- React Native + Expo (SDK 51) + TypeScript
- React Navigation (Bottom Tabs + Native Stack)
- Axios (camada HTTP)
- AsyncStorage (sessão do usuário)
- expo-print, expo-sharing, expo-file-system (geração/compartilhamento do PDF)
- expo-clipboard (copiar código de autenticidade)

## 4. Como instalar

```bash
git clone <repo>
cd CorujaDocs
npm install
```

## 5. Como executar

```bash
npx expo start
```

Escaneie o QR Code exibido com o app **Expo Go** (Android/iOS) ou rode em um
emulador com `npx expo start --android` / `--ios`.

**Credenciais de demonstração** (exibidas também na tela de login):

```
Alan Turing      → Matrícula: 10032026  | Senha: coruja123
Albert Einstein  → Matrícula: 30032026  | Senha: coruja123
Thomas Edison    → Matrícula: 40032026  | Senha: coruja123
```

## 6. Como configurar a API

A URL da API fica centralizada em `src/services/api.ts`:

```ts
export const API_URL = "https://api.exemplo.com";
export const USE_MOCK = true;
```

Por padrão, `USE_MOCK` fica `true` e todas as chamadas são respondidas por
`src/services/mockApi.ts` (dados fictícios em memória) — útil para testar o
app sem precisar subir nada além do Expo.

O projeto agora inclui também um **backend real** em `backend/` (Node.js +
Express + MySQL), implementando os mesmos endpoints. Para usá-lo:

1. Siga o `backend/README.md` para instalar, configurar o `.env`, criar o
   banco (`sql/schema.sql`) e popular os estudantes fictícios (`npm run seed`);
2. Rode o backend (`npm run dev` dentro de `backend/`);
3. Em `src/services/api.ts`, defina:
   ```ts
   export const API_URL = "http://SEU_IP_LOCAL:3333";
   export const USE_MOCK = false;
   ```

Nenhuma tela do app precisa ser alterada — os services (`authService`,
`studentService`, `documentService`) já fazem essa ponte entre mock e
backend real.

## 7. Estrutura de pastas

```
src/
  components/     Componentes reutilizáveis (Button, Input, DocumentCard...)
  screens/        Todas as telas do app
  navigation/      RootNavigator, MainTabs e stacks internas
  services/       api.ts, mockApi.ts, authService, studentService, documentService
  contexts/       AuthContext (login/logout/sessão)
  utils/          storage.ts, pdfTemplate.ts, pdfGenerator.ts
  types/          Interfaces TypeScript do domínio
  constants/      Tema visual (cores, tipografia, espaçamento)

backend/          Web Service REST real (Node.js + Express + MySQL) — ver backend/README.md
```

## 8. Endpoints

```
POST /auth/login
GET  /students/{id}
GET  /students/{id}/documents
GET  /students/{id}/requests
POST /documents/student-declaration
GET  /documents/{id}
GET  /documents/validate/{authenticationCode}
```

O mais importante é `POST /documents/student-declaration`: recebe a
finalidade e observações da declaração e retorna o documento com um
`authenticationCode` único.

## 9. Como funciona a geração do PDF

1. O estudante preenche a finalidade da declaração e confirma;
2. O app chama `POST /documents/student-declaration`;
3. A API (mock ou real) retorna o documento com o código de autenticidade;
4. `src/utils/pdfTemplate.ts` monta o HTML/CSS da declaração (cabeçalho
   institucional genérico, texto formal dinâmico, QR Code e aviso de
   protótipo);
5. `src/utils/pdfGenerator.ts` converte esse HTML em PDF via `expo-print` e
   permite baixar (`expo-file-system`) ou compartilhar (`expo-sharing`).

Nenhum dado é fixo no template: nome, matrícula, curso, período, turno,
previsão de término, data de emissão e código de autenticidade vêm sempre da
resposta da API.

## 10. Como funciona o código de autenticidade

- Gerado no formato `FAETERJ-XXXX-XXXX`;
- Único por documento (a API mock garante isso ao gerar);
- Retornado pela API e armazenado junto ao documento;
- Exibido na tela de emissão e no PDF, com botão de copiar
  (componente `AuthenticationCode`).

## 11. Como funciona a validação

Qualquer pessoa — logada ou não — pode ir até a aba **🔎 Validar Documento**,
digitar o código de autenticidade e consultar
`GET /documents/validate/{authenticationCode}`. O resultado pode ser:

- 🟢 **Documento encontrado** — exibe os dados do documento;
- 🔴 **Documento não encontrado** — código inexistente;
- 🟠 **Documento cancelado** — preparado para um status futuro de cancelamento.

A aba de validação é acessível mesmo sem login, pois a autenticidade de um
documento pode precisar ser conferida por terceiros (ex.: RH de uma empresa).

## 12. Como funciona o QR Code

O QR Code impresso no PDF aponta para uma URL de demonstração
(`https://corujadocs.example.com/validate/{authenticationCode}`) contendo o
mesmo `authenticationCode` usado na validação manual — ambos os caminhos
consultam a mesma informação na API.

## 13. Limitações do protótipo

- A API é mockada em memória: os dados são reiniciados a cada nova execução
  do app;
- Apenas a **Declaração de Estudante** possui fluxo completo de emissão;
  Histórico Escolar e Carteira de Estudante aparecem apenas como exemplos de
  lista de documentos;
- O QR Code do PDF usa um serviço público de geração de imagem, sem leitura
  de QR Code pelo próprio app;
- Existe um backend real opcional em `backend/` (Node.js + Express + MySQL,
  com autenticação JWT e senhas com hash bcrypt) — veja `backend/README.md`.
  Por padrão o app ainda usa a API mockada (`USE_MOCK = true`) para facilitar
  testes sem precisar subir banco de dados;
- Assinatura digital e certificado digital não foram implementados —
  seguem como evolução futura abaixo.

## Possíveis evoluções

- Banco de dados PostgreSQL (alternativa ao MySQL já implementado);
- Autenticação via OAuth institucional (JWT já implementado);
- Integração institucional real (sem dados fictícios);
- Assinatura digital e certificado digital;
- Validação oficial reconhecida pela instituição;
- Notificações push sobre status de solicitações;
- Histórico acadêmico completo (notas, frequência);
- Outros tipos de documentos (atestado de matrícula, boletim etc.);
- Portal web público de validação (além do app);
- Leitura de QR Code diretamente pelo aplicativo.
